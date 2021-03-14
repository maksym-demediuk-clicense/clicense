#include "main.h"

extern "C" _declspec(dllexport) ILicense* GetInterface()
{
    return new CLicense();
}