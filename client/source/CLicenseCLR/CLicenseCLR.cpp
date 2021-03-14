#include "CLicenseCLR.h"

using namespace System;
using namespace System::Runtime::InteropServices;

CLicenseCLR::CLicenseCLR() : m_pLicenseManager(new ILicenseWrapper())
{
}


CLicenseCLR::~CLicenseCLR()
{
    delete m_pLicenseManager;
}

void CLicenseCLR::SetupVersion(unsigned int uiVersion)
{
    m_pLicenseManager->GetInterface()->SetupVersion(uiVersion);
}

void CLicenseCLR::SetupProductName(System::String^ szProduct)
{
    IntPtr ptrToNativeString = Marshal::StringToHGlobalAnsi(szProduct);
    char* nativeString = static_cast<char*>(ptrToNativeString.ToPointer());
    m_pLicenseManager->GetInterface()->SetupProductName(nativeString);
}

void CLicenseCLR::SetupFailCallback(LicenseCallbackDelegate^ pCallback)
{
    IntPtr stubPointer = Marshal::GetFunctionPointerForDelegate(pCallback);
    LicenseCallbackProc functionPointer = static_cast<LicenseCallbackProc>(stubPointer.ToPointer());
    m_pLicenseManager->GetInterface()->SetupFailCallback(functionPointer);
}

void CLicenseCLR::SetupUpdateCallback(LicenseCallbackDelegate^ pCallback)
{
    IntPtr stubPointer = Marshal::GetFunctionPointerForDelegate(pCallback);
    LicenseCallbackProc functionPointer = static_cast<LicenseCallbackProc>(stubPointer.ToPointer());
    m_pLicenseManager->GetInterface()->SetupUpdateCallback(functionPointer);
}

void CLicenseCLR::SetupSuccessCallback(LicenseCallbackDelegate^ pCallback)
{
    IntPtr stubPointer = Marshal::GetFunctionPointerForDelegate(pCallback);
    LicenseCallbackProc functionPointer = static_cast<LicenseCallbackProc>(stubPointer.ToPointer());
    m_pLicenseManager->GetInterface()->SetupSuccessCallback(functionPointer);
}

void CLicenseCLR::VerifyLicense()
{
    m_pLicenseManager->GetInterface()->VerifyLicense();
}

CLicenseCLR::eLicenseResponse CLicenseCLR::ActivateLicense()
{
    auto response = m_pLicenseManager->GetInterface()->ActivateLicense();
    return static_cast<CLicenseCLR::eLicenseResponse>(response);
}

System::String^ CLicenseCLR::EncryptData(System::String^ data)
{
    return "test";
}

System::String^ CLicenseCLR::DecryptData(System::String^ data)
{
    return "test";
}

void CLicenseCLR::DummyMethod1()
{
    m_pLicenseManager->GetInterface()->DummyMethod1();
}

void CLicenseCLR::DummyMethod2(unsigned int a1)
{
    m_pLicenseManager->GetInterface()->DummyMethod2(reinterpret_cast<void*>(a1));
}

void CLicenseCLR::DummyMethod3(unsigned int a1, unsigned int a2)
{
    m_pLicenseManager->GetInterface()->DummyMethod3(reinterpret_cast<void*>(a1), reinterpret_cast<void*>(a2));
}

void CLicenseCLR::DummyMethod4(unsigned int a1)
{
    m_pLicenseManager->GetInterface()->DummyMethod4(reinterpret_cast<void*>(a1));
}

void CLicenseCLR::DummyMethod5(unsigned int a1)
{
    m_pLicenseManager->GetInterface()->DummyMethod5(reinterpret_cast<void*>(a1));
}

void CLicenseCLR::DummyMethod6(unsigned int a1, unsigned int a2, unsigned int a3)
{
    m_pLicenseManager->GetInterface()->DummyMethod6(reinterpret_cast<void*>(a1), reinterpret_cast<void*>(a2), reinterpret_cast<void*>(a3));
}

void CLicenseCLR::DummyMethod7()
{
    m_pLicenseManager->GetInterface()->DummyMethod7();
}

void CLicenseCLR::DummyMethod8()
{
    m_pLicenseManager->GetInterface()->DummyMethod8();
}

void CLicenseCLR::DummyMethod9(unsigned int a1, unsigned int a2, unsigned int a3, unsigned int a4)
{
    m_pLicenseManager->GetInterface()->DummyMethod9(reinterpret_cast<void*>(a1), reinterpret_cast<void*>(a2), reinterpret_cast<void*>(a3), reinterpret_cast<void*>(a4));
}
