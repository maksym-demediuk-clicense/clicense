#pragma once
class CLicense : public ILicense
{
public:
    CLicense();
    virtual ~CLicense();

    virtual void SetupVersion(const unsigned int uiVersion) override;
    virtual void SetupProductName(const char* szProduct) override;
    virtual void SetupFailCallback(LicenseCallbackProc pCallback) override;
    virtual void SetupUpdateCallback(LicenseCallbackProc pCallback) override;
    virtual void SetupSuccessCallback(LicenseCallbackProc pCallback) override;
    virtual void VerifyLicense() override;
    virtual eActivationResponse ActivateLicense() override;
    virtual std::shared_ptr<std::string> EncryptData(const std::string& data)  override;
    virtual std::shared_ptr<std::string> DecryptData(const std::string& data)  override;
    virtual void DummyMethod1() override;
    virtual void DummyMethod2(void*) override;
    virtual void DummyMethod3(void*, void*) override;
    virtual void DummyMethod4(void*) override;
    virtual void DummyMethod5(void*) override;
    virtual void DummyMethod6(void*, void*, void*) override;
    virtual void DummyMethod7() override;
    virtual void DummyMethod8() override;
    virtual void DummyMethod9(void*, void*, void*, void*) override;

private:

    unsigned int m_uiVersion;
    std::string m_strVersion;
    std::string m_strUsername;
    std::string m_strHardwareID;
    std::string m_strProductName;
    LicenseCallbackProc m_pFailCallback;
    LicenseCallbackProc m_pUpdateCallback;
    LicenseCallbackProc m_pSuccessCallback;
    int m_aDummyVariable[32];

    std::string GetUsername();
    std::string GetPublicKey();
    std::string GetPrivateKey();
    std::string GetEthProvider();
    std::string GetEthContractAddress();
    std::string GetHardwareID();
    std::string GetCallbacks();
    std::string GetProductName();
    std::string GetVersion();

    std::string ReadRegistryEntry(const std::string& entry);
};
