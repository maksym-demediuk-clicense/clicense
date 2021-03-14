#pragma once
#include <string>
#include <memory>

#pragma managed(push, off)
typedef void(_cdecl *LicenseCallbackProc)();
#pragma managed(pop)

class ILicense
{
public:
    ILicense();
    virtual ~ILicense();

    enum eActivationResponse
    {
        RESPONSE_INVALID_DATA = 0,
        RESPONSE_ALREADY_ACTIVATED = 1,
        RESPONSE_ACTIVATED = 2,
        MAX_VALUE
    };

    virtual void SetupVersion(const unsigned int uiVersion) = 0;
    virtual void SetupProductName(const char* szProduct) = 0;
    virtual void SetupFailCallback(LicenseCallbackProc pCallback) = 0;
    virtual void SetupUpdateCallback(LicenseCallbackProc pCallback) = 0;
    virtual void SetupSuccessCallback(LicenseCallbackProc pCallback) = 0;
    virtual void VerifyLicense() = 0;
    virtual eActivationResponse ActivateLicense() = 0;
    virtual std::shared_ptr<std::string> EncryptData(const std::string& data) = 0;
    virtual std::shared_ptr<std::string> DecryptData(const std::string& data) = 0;

    virtual void DummyMethod1() = 0;
    virtual void DummyMethod2(void*) = 0;
    virtual void DummyMethod3(void*, void*) = 0;
    virtual void DummyMethod4(void*) = 0;
    virtual void DummyMethod5(void*) = 0;
    virtual void DummyMethod6(void*, void*, void*) = 0;
    virtual void DummyMethod7() = 0;
    virtual void DummyMethod8() = 0;
    virtual void DummyMethod9(void*, void*, void*, void*) = 0;
};
