#include "ILicenseWrapper.h"

#include <Windows.h>

typedef ILicense*(_cdecl *GetInterfaceProc)();

ILicenseWrapper::ILicenseWrapper()
{
    HMODULE dll = LoadLibraryA("CLicense.dll");
    GetInterfaceProc GetInterface = (GetInterfaceProc)GetProcAddress(dll, "GetInterface");
    m_pLicenseManager = GetInterface();
    //FreeLibrary(dll);
}


ILicenseWrapper::~ILicenseWrapper()
{
}

ILicense* ILicenseWrapper::GetInterface()
{
    return m_pLicenseManager;
}
