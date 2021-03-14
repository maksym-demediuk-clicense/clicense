#pragma once

#include <string>

namespace hwid
{
    enum eErrorState
    {
        ERROR_NONE,
        ERROR_COINITIALIZE,
        ERROR_COCREATEINSTANCE,
        ERROR_CONNECTSERVER,
        ERROR_SETPROXYBLANKET,
        ERROR_GETWINDIRECTORY,
        ERROR_INVALIDVALUE,
        ERROR_EXECQUERY,
        ERROR_ENUMNEXT,
        ERROR_OBJECTGET,
        ERROR_EXCEPTION,
    };

    std::string GenerateHardwareID();
    eErrorState GetLastError();
}
