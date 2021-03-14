#pragma once

#define WIN32_LEAN_AND_MEAN
#include "../CLicense/ILicense.h"

class ILicenseWrapper
{
public:
    ILicenseWrapper();
    ~ILicenseWrapper();

    ILicense* GetInterface();

private:
    ILicense* m_pLicenseManager;
};
