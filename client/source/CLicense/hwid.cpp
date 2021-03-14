#include "hwid.h"
#include "crypt.h"

#include <shlobj.h>
#include <wbemcli.h>
#include <algorithm>

static hwid::eErrorState s_eErrorState = hwid::eErrorState::ERROR_NONE;

std::wstring GetWmiProperties();
std::wstring ProcessWmiProperty(BSTR prop, bool check = true);
std::wstring GetWmiProperty(IWbemServices* services, const wchar_t* classname, const wchar_t* property, bool check);
std::wstring GetHddProperty(IWbemServices* services, bool check = true);
std::wstring GetNetworkProperty(IWbemServices* services, bool check = true);
std::wstring GetBaseBoardProperty(IWbemServices* services, bool check = true);
std::wstring GetComputerSystemProperties(IWbemServices* services, bool check = true);

const CLSID local_CLSID_WbemLocator = { 0x4590F811, 0x1D3A, 0x11D0, { 0x89, 0x1F, 0, 0xAA, 0, 0x4B, 0x2E, 0x24 } };
const IID local_IID_IWbemLocator = { 0xdc12a687, 0x737f, 0x11cf, { 0x88, 0x4d, 0, 0xAA, 0, 0x4B, 0x2E, 0x24 } };

namespace hwid
{
    std::string GenerateHardwareID()
    {
        s_eErrorState = ERROR_NONE;
        std::wstring hwid = L"";

        if (SUCCEEDED(CoInitialize(nullptr)))
        {
            hwid = GetWmiProperties();
            CoUninitialize();
        }
        else
        {
            s_eErrorState = ERROR_COINITIALIZE;
        }

        return crypt::Hash_MD5(std::string(hwid.begin(), hwid.end()));
    }

    hwid::eErrorState GetLastError()
    {
        return s_eErrorState;
    }
}

std::wstring GetWmiProperties()
{
    std::wstring result;
    IWbemLocator* locator;
    if (SUCCEEDED(CoCreateInstance(local_CLSID_WbemLocator, nullptr, CLSCTX_INPROC_SERVER, local_IID_IWbemLocator, reinterpret_cast<void**>(&locator))))
    {
        IWbemServices* services;
        BSTR net = L"ROOT\\CIMV2";
        if (SUCCEEDED(locator->ConnectServer(net, nullptr, nullptr, nullptr, WBEM_FLAG_CONNECT_USE_MAX_WAIT, nullptr, nullptr, &services)))
        {
            if (SUCCEEDED(CoSetProxyBlanket(services, RPC_C_AUTHN_WINNT, RPC_C_AUTHZ_NONE, nullptr, RPC_C_AUTHN_LEVEL_CALL, RPC_C_IMP_LEVEL_IMPERSONATE, nullptr, EOAC_NONE)))
            {
                try
                {
                    result += GetNetworkProperty(services);
                    result += GetHddProperty(services);
                    result += GetBaseBoardProperty(services);
                    result += GetComputerSystemProperties(services);
                }
                catch (...)
                {
                    s_eErrorState = hwid::eErrorState::ERROR_EXCEPTION;
                    result.clear();
                }
            }
            else
            {
                s_eErrorState = hwid::eErrorState::ERROR_SETPROXYBLANKET;
            }
            services->Release();
        }
        else
        {
            s_eErrorState = hwid::eErrorState::ERROR_CONNECTSERVER;
        }
        locator->Release();
    }
    else
    {
        s_eErrorState = hwid::eErrorState::ERROR_COCREATEINSTANCE;
    }

    return result;
}

std::wstring GetWmiProperty(IWbemServices* services, const wchar_t* classname, const wchar_t* property, bool check)
{
    std::wstring result;
    IEnumWbemClassObject* enumerator = nullptr;
    try
    {
        std::wstring query = L"SELECT * FROM ";
        query += classname;;
        BSTR lang(L"WQL");
        if (SUCCEEDED(services->ExecQuery(lang, const_cast<BSTR>(query.c_str()), WBEM_FLAG_FORWARD_ONLY | WBEM_FLAG_RETURN_IMMEDIATELY, nullptr, &enumerator)))
        {
            IWbemClassObject* object;
            ULONG returned = 0;
            if (SUCCEEDED(enumerator->Next(WBEM_INFINITE, 1, &object, &returned)) && (returned > 0))
            {
                VARIANT value;
                CIMTYPE type;
                if (SUCCEEDED(object->Get(property, 0, &value, &type, nullptr)))
                {
                    if ((type == CIM_STRING) && (value.vt == VT_BSTR))
                        result += ProcessWmiProperty(V_BSTR(&value), check);

                    //HIDLog::Write(std::wstring() + L"WMI property \"" + property + L"\" FOR CLASS \"" +
                    //              classname + L"\" = \"" + result + L"\"", LogLevel::Debug);
                    VariantClear(&value);
                }
                else
                {
                    s_eErrorState = hwid::eErrorState::ERROR_OBJECTGET;
                }
                object->Release();
            }
            else
            {
                s_eErrorState = hwid::eErrorState::ERROR_ENUMNEXT;
            }
        }
        else
        {
            s_eErrorState = hwid::eErrorState::ERROR_EXECQUERY;
        }
    }
    catch (...)
    {
        result.clear();
    }

    if (enumerator)
        enumerator->Release();

    return result;
}

std::wstring GetHddProperty(IWbemServices* services, bool check)
{
    std::wstring from = L"win32_logicaldisk WHERE deviceid=\"";
    const size_t buffSize = 64;
    wchar_t locationBuffer[buffSize];
    if (SUCCEEDED(GetWindowsDirectoryW(locationBuffer, buffSize)))
    {
        from.append(1, *locationBuffer);
        from.append(L":\"");
        return GetWmiProperty(services, from.c_str(), L"VolumeSerialNumber", check);
    }
    else
    {
        s_eErrorState = hwid::eErrorState::ERROR_GETWINDIRECTORY;
    }

    //HIDLog::Write("Could not get ID of volume HDD", LogLevel::Debug);
    return std::wstring();
}

std::wstring GetNetworkProperty(IWbemServices* services, bool check /*= true*/)
{
    const wchar_t* wszNetworkClass = L"Win32_NetworkAdapter WHERE Manufacturer != \'Microsoft\' AND NOT PNPDeviceID LIKE \'ROOT%\\\\\'";
    const wchar_t* wszNetworkProperty = L"MACAddress";
    return GetWmiProperty(services, wszNetworkClass, wszNetworkProperty, check);
}

std::wstring GetBaseBoardProperty(IWbemServices* services, bool check /*= true*/)
{
    const wchar_t* wszBaseBoardClass = L"Win32_BaseBoard";
    const wchar_t* wszBaseBoardProperty = L"SerialNumber";
    return GetWmiProperty(services, wszBaseBoardClass, wszBaseBoardProperty, check);
}

std::wstring GetComputerSystemProperties(IWbemServices* services, bool check /*= true*/)
{
    const wchar_t* wszComputerSystemClass = L"Win32_ComputerSystemProduct";
    const wchar_t* wszComputerSystemProperties[2] = { L"UUID", L"IdentifyingNumber" };
    std::wstring result = L"";
    result += GetWmiProperty(services, wszComputerSystemClass, wszComputerSystemProperties[0], check);
    result += GetWmiProperty(services, wszComputerSystemClass, wszComputerSystemProperties[1], check);
    return result;
}

std::wstring ProcessWmiProperty(BSTR prop, bool check)
{
    std::wstring value(prop, SysStringLen(prop));
    std::transform(value.begin(), value.end(), value.begin(), tolower);

    const std::wstring empty_guid(L"00000000-0000-0000-0000-000000000000");
    const std::wstring broken_guid(L"ffffffff-ffff-ffff-ffff-ffffffffffff");
    const std::wstring empty_oem(L"to be filled by o.e.m.");

    if (check)
    {
        if ((value == empty_guid) || (value == broken_guid) || (value == empty_oem))
        {
            s_eErrorState = hwid::eErrorState::ERROR_INVALIDVALUE;
            return std::wstring();
        }
    }

    return value;
}