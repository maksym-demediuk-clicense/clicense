#include "os.h"

static os::eErrorState s_eErrorState = os::eErrorState::ERROR_NONE;

namespace os
{
    std::string GetUsername()
    {
        CHAR szUserName[256];
        DWORD len = sizeof(szUserName);
        if (GetUserNameA(szUserName, &len) != 0)
        {
            return szUserName;
        }
        s_eErrorState = os::eErrorState::ERROR_EMPTYUSERNAME;
        return std::string("<blank_username>");
    }

    std::string ReadRegistryKey(const std::string& location, const std::string& name)
    {
        // Verify and open registry key
        HKEY key;
        long ret = RegOpenKeyExA(HKEY_CURRENT_USER, location.c_str(), 0, KEY_QUERY_VALUE, &key);
        if (ret != ERROR_SUCCESS)
        {
            s_eErrorState = os::eErrorState::ERROR_REGOPENKEY;
            return std::string();
        }

        // Read value if exists
        CONST DWORD max_size = 1024;
        CHAR value[max_size];
        DWORD read_size = sizeof(value);
        ret = RegQueryValueExA(key, name.c_str(), NULL, NULL, (LPBYTE)value, &read_size);
        RegCloseKey(key);
        if ((ret != ERROR_SUCCESS) || (read_size > max_size))
        {
            s_eErrorState = os::eErrorState::ERROR_REGQUERYVALUE;
            return std::string();
        }

        // Return clean string
        std::string stringValue = std::string(value, (size_t)read_size - 1);
        size_t i = stringValue.length();
        while (i > 0 && stringValue[i - 1] == '\0')
        {
            --i;
        }
        return stringValue.substr(0, i);
    }
}
