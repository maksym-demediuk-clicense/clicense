#pragma once

#include <vector>
#include <string>
#include <Windows.h>

namespace os
{
    enum eErrorState
    {
        ERROR_NONE,
        ERROR_REGOPENKEY,
        ERROR_REGQUERYVALUE,
        ERROR_EMPTYKEY,
        ERROR_EMPTYUSERNAME,
    };

    std::string GetUsername();
    std::string ReadRegistryKey(const std::string& location, const std::string& name);
    eErrorState GetLastError();
}
