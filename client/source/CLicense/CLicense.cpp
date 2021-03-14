#define LICENSE_SEPARATOR_CHAR '/'

#include "main.h"

CLicense::CLicense()
{
    m_pFailCallback = nullptr;
    m_pUpdateCallback = nullptr;
    m_pSuccessCallback = nullptr;
}

CLicense::~CLicense()
{
}

#pragma region SetupRoutine
void CLicense::SetupVersion(const unsigned int uiVersion)
{
    m_uiVersion = uiVersion;

    std::stringstream ss;
    ss << std::hex << uiVersion;
    m_strVersion = ss.str();
}

void CLicense::SetupProductName(const char* szProduct)
{
    m_strProductName = szProduct;
}

void CLicense::SetupFailCallback(LicenseCallbackProc pCallback)
{
    m_pFailCallback = pCallback;
}

void CLicense::SetupUpdateCallback(LicenseCallbackProc pCallback)
{
    m_pUpdateCallback = pCallback;
}

void CLicense::SetupSuccessCallback(LicenseCallbackProc pCallback)
{
    m_pSuccessCallback = pCallback;
}
#pragma endregion SetupRoutine

void CLicense::VerifyLicense()
{
    // Collect, format and encrypt validation data

    auto version = GetVersion();
    auto callbacks = GetCallbacks();
    auto hwid = GetHardwareID();
    auto lickey = GetPublicKey();

    std::stringstream data, enc_data, key;
    data << version
        << hwid
        << lickey
        << callbacks;

    enc_data << "data: " << crypt::Encrypt_AES(data.str());
    key << "key: " << crypt::Key_AES();

    IContract* contract = new CLicenseContract(GetEthContractAddress());
    auto eth = new CEthereumClient(GetEthProvider());
    auto response = eth->WithContract(contract)->WithData(data.str())->Call();

    // Call server response callback
    auto callback = std::stoi(response, nullptr, 16);
    reinterpret_cast<LicenseCallbackProc>(callback)();

    delete eth;
    delete contract;
}

ILicense::eActivationResponse CLicense::ActivateLicense()
{
    auto public_key = GetPublicKey();
    auto private_key = GetPrivateKey();
    auto hwid = GetHardwareID();

    std::stringstream data, enc_data, key;
    data << public_key << private_key << hwid;

    enc_data << "data: " << crypt::Encrypt_AES(data.str());
    key << "key: " << crypt::Key_AES();

    std::vector<std::string> headers;
    headers.emplace_back(enc_data.str());
    headers.emplace_back(key.str());

    const auto activation_url = "https://clicense.tk/activate.php";
    std::string response = network::SendHttpGet(activation_url, &headers);

    ILicense::eActivationResponse result = ILicense::RESPONSE_INVALID_DATA;

    auto value = std::stoi(response);
    if (value < ILicense::eActivationResponse::MAX_VALUE)
    {
        result = static_cast<ILicense::eActivationResponse>(value);
    }

    return result;
}

std::shared_ptr<std::string> CLicense::EncryptData(const std::string& data)
{
    return std::make_shared<std::string>(crypt::Encrypt_AES(data));
}

std::shared_ptr<std::string> CLicense::DecryptData(const std::string& data)
{
    return std::make_shared<std::string>(crypt::Decrypt_AES(data));
}

std::string CLicense::GetUsername()
{
    if (m_strUsername.empty())
    {
        m_strUsername = os::GetUsername();
        m_strUsername.erase(
            std::remove(m_strUsername.begin(), m_strUsername.end(), LICENSE_SEPARATOR_CHAR),
            m_strUsername.end());
    }
    return m_strUsername;
}

std::string CLicense::GetPublicKey()
{
    const auto LICENSE_KEY_LENGTH = 16;
    return convert::string_to_fixed(ReadRegistryEntry("public_key"), LICENSE_KEY_LENGTH);
}

std::string CLicense::GetPrivateKey()
{
    return ReadRegistryEntry("private_key");
}

std::string CLicense::GetEthProvider()
{
    return ReadRegistryEntry("provider");
}

std::string CLicense::GetEthContractAddress()
{
    return ReadRegistryEntry("contract_addr");
}

std::string CLicense::GetHardwareID()
{
    if (m_strHardwareID.empty())
    {
        m_strHardwareID = hwid::GenerateHardwareID();
        //m_strHardwareID.erase(
        //    std::remove(m_strHardwareID.begin(), m_strHardwareID.end(), LICENSE_SEPARATOR_CHAR),
        //    m_strHardwareID.end());
    }

    const auto LICENSE_HWID_LENGTH = 32;
    return convert::string_to_fixed(m_strHardwareID, LICENSE_HWID_LENGTH);
}

std::string CLicense::GetCallbacks()
{
    const auto LICENSE_CALLBACK_LENGTH = sizeof(unsigned int) * 2;
    std::stringstream result;
    result << std::right << std::setfill('0')
        << std::setw(LICENSE_CALLBACK_LENGTH) << std::hex << reinterpret_cast<unsigned int>(m_pFailCallback)
        << std::setw(LICENSE_CALLBACK_LENGTH) << std::hex << reinterpret_cast<unsigned int>(m_pUpdateCallback)
        << std::setw(LICENSE_CALLBACK_LENGTH) << std::hex << reinterpret_cast<unsigned int>(m_pSuccessCallback);

    return result.str();
}

std::string CLicense::GetProductName()
{
    return m_strProductName;
}

std::string CLicense::GetVersion()
{
    return convert::integral_to_abi(m_uiVersion, 32);
    //const auto LICENSE_VERSION_LENGTH = 32;
    //return convert::string_to_fixed(m_strVersion, LICENSE_VERSION_LENGTH);
}

std::string CLicense::ReadRegistryEntry(const std::string& entry)
{
    return os::ReadRegistryKey("SOFTWARE\\LicenseManager\\" + m_strProductName, entry);
}

#pragma region DummyMethods
void CLicense::DummyMethod1()
{
    std::string dummy = hwid::GenerateHardwareID();
    DummyMethod2(&dummy);
    for (int i = 0; i < 16; i++)
    {
        for (auto c : dummy)
        {
            m_aDummyVariable[i] = ((((c & 0xD) ^ 0xFF) << 0x1) ^ 0xAA) % 0xDC;
        }
    }
}

void CLicense::DummyMethod2(void* a)
{
    std::string dummy = reinterpret_cast<std::string&>(a);
    for (auto c : dummy)
    {
        switch (c)
        {
        case 0xDE:
        case 0xAD:
        case 0xBE:
        case 0xEF:
            m_aDummyVariable[0xF] &= c;
            break;
        default:
            throw std::exception();
        }
    }
}

void CLicense::DummyMethod3(void*, void*)
{
    // #TODO
    throw std::logic_error("The method or operation is not implemented.");
}

void CLicense::DummyMethod4(void*)
{
    void* dummy = nullptr;
    do
    {
        DummyMethod5(dummy);
    } while (dummy == nullptr);
}

void CLicense::DummyMethod5(void*)
{
}

void CLicense::DummyMethod6(void* a, void* b, void* c)
{
    long dummy1 = 1;
    long _a = (long)a;
    long _b = (long)b;
    long _c = (long)c;
    while (_b)
    {
        if (_b & 1)
            dummy1 = (dummy1 * _a) % _c;
        _b >>= 1;
        _a = (_a * _a) % _c;
    }
    m_aDummyVariable[dummy1] = _a ^_b ^_c;
}

void CLicense::DummyMethod7()
{
    // #TODO
    throw std::logic_error("The method or operation is not implemented.");
}

void CLicense::DummyMethod8()
{
    // #TODO
    throw std::logic_error("The method or operation is not implemented.");
}

void CLicense::DummyMethod9(void*, void*, void*, void*)
{
    // #TODO
    throw std::logic_error("The method or operation is not implemented.");
}
#pragma endregion DummyMethods