#pragma once

#include <vector>
#include <string>

namespace network
{
    enum eErrorState
    {
        ERROR_NONE,
        ERROR_CURL_INIT,
        ERROR_CURL_PERFORM
    };

    std::string SendHttpGet(const std::string& url, const std::vector<std::string>* headers, bool secure = true);
    std::string SendHttpPost(const std::string& url, const std::string& header, const std::string& data, bool secure);
    eErrorState GetLastError();
}
