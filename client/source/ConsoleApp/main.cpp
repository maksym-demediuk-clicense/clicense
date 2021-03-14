#include <iostream>
#include <conio.h>
#include <Windows.h>
#include <functional>
#include "../CLicense/ILicense.h"

typedef ILicense*(_cdecl *GetInterfaceProc)();

void Success()
{
    std::cout << "Success\n";
}

void Fail()
{
    std::cout << "Fail\n";
}

void Update()
{
    std::cout << "Update\n";
}

void main()
{
    HMODULE dll = LoadLibraryA("CLicense.dll");
    GetInterfaceProc GetInterface = (GetInterfaceProc)GetProcAddress(dll, "GetInterface");
    ILicense* pLicense = GetInterface();

    pLicense->SetupSuccessCallback(Success);
    pLicense->SetupFailCallback(Fail);
    pLicense->SetupUpdateCallback(Update);
    pLicense->SetupProductName("TestProd");
    pLicense->SetupVersion(1);

    pLicense->VerifyLicense();
    _getch();
}