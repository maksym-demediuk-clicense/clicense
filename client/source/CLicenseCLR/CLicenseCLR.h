#pragma once
#include "ILicenseWrapper.h"



public ref class CLicenseCLR
{
public:
    CLicenseCLR();
    ~CLicenseCLR();

    delegate void LicenseCallbackDelegate();

    enum class eLicenseResponse
    {
        RESPONSE_INVALID_DATA = 0,
        RESPONSE_ALREADY_ACTIVATED = 1,
        RESPONSE_ACTIVATED = 2
    };

    void SetupVersion(unsigned int uiVersion);

    void SetupProductName(System::String^ szProduct);
    void SetupFailCallback(LicenseCallbackDelegate^ pCallback);
    void SetupUpdateCallback(LicenseCallbackDelegate^ pCallback);
    void SetupSuccessCallback(LicenseCallbackDelegate^ pCallback);
    void VerifyLicense();
    eLicenseResponse ActivateLicense();
    System::String^ EncryptData(System::String^ data);
    System::String^ DecryptData(System::String^ data);

    void DummyMethod1();
    void DummyMethod2(unsigned int a1);
    void DummyMethod3(unsigned int a1, unsigned int a2);
    void DummyMethod4(unsigned int a1);
    void DummyMethod5(unsigned int a1);
    void DummyMethod6(unsigned int a1, unsigned int a2, unsigned int a3);
    void DummyMethod7();
    void DummyMethod8();
    void DummyMethod9(unsigned int a1, unsigned int a2, unsigned int a3, unsigned int a4);

private:
    ILicenseWrapper* m_pLicenseManager;
};

