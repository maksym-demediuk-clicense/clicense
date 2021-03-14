#include "network.h"

#define CPPHTTPLIB_OPENSSL_SUPPORT

#include <curl/curl.h>

static std::string buffer;
static network::eErrorState s_eErrorState = network::eErrorState::ERROR_NONE;

static int writer(char *data, size_t size, size_t nmemb,
                  std::string *writerData)
{
    if (writerData == NULL)
        return 0;

    writerData->append(data, size*nmemb);

    return size * nmemb;
}

namespace network
{
    std::string SendHttpGet(const std::string& url, const std::vector<std::string>* headers, bool secure)
    {
        CURL *curl;
        curl = curl_easy_init();
        if (curl != nullptr)
        {
            buffer.clear();

            if (headers && headers->empty() == false)
            {
                struct curl_slist *chunk = NULL;
                for (const auto& header : *headers)
                {
                    chunk = curl_slist_append(chunk, header.c_str());
                }
                curl_easy_setopt(curl, CURLOPT_HTTPHEADER, chunk);
            }

            curl_easy_setopt(curl, CURLOPT_URL, url.c_str());
            curl_easy_setopt(curl, CURLOPT_USERAGENT, "CLicense/1.0");
            curl_easy_setopt(curl, CURLOPT_HTTPGET, 1);
            curl_easy_setopt(curl, CURLOPT_WRITEFUNCTION, writer);
            curl_easy_setopt(curl, CURLOPT_WRITEDATA, &buffer);
            if (secure)
            {
                curl_easy_setopt(curl, CURLOPT_SSL_VERIFYPEER, 0L);
                curl_easy_setopt(curl, CURLOPT_SSL_VERIFYHOST, 0L);
            }

            curl_easy_perform(curl);
            curl_easy_cleanup(curl);

            return buffer;
        }
        else
        {
            s_eErrorState = network::eErrorState::ERROR_CURL_INIT;
        }
    }

    std::string SendHttpPost(const std::string& url, const std::string& header, const std::string& data, bool secure)
    {
        CURL *curl;
        curl_global_init(CURL_GLOBAL_ALL);
        curl = curl_easy_init();
        if (curl != nullptr)
        {
            buffer.clear();

            if (header.empty() == false)
            {
                struct curl_slist* chunk = NULL;
                chunk = curl_slist_append(chunk, header.c_str());
                curl_easy_setopt(curl, CURLOPT_HTTPHEADER, chunk);
            }

            curl_easy_setopt(curl, CURLOPT_URL, url.c_str());
            curl_easy_setopt(curl, CURLOPT_USERAGENT, "CLicense/1.0");
            curl_easy_setopt(curl, CURLOPT_HTTPPOST, 1);
            curl_easy_setopt(curl, CURLOPT_POSTFIELDS, data.c_str());
            curl_easy_setopt(curl, CURLOPT_WRITEFUNCTION, writer);
            curl_easy_setopt(curl, CURLOPT_WRITEDATA, &buffer);
            if (secure)
            {
                curl_easy_setopt(curl, CURLOPT_SSL_VERIFYPEER, 1L);
                curl_easy_setopt(curl, CURLOPT_SSL_VERIFYHOST, 0L);
                curl_easy_setopt(curl, CURLOPT_CAINFO, "cacert.pem");
            }

            auto code = curl_easy_perform(curl);

            if (code != CURLE_OK)
            {
                s_eErrorState = network::eErrorState::ERROR_CURL_PERFORM;
            }
            curl_easy_cleanup(curl);
            curl_global_cleanup();

            return buffer;
        }
        else
        {
            s_eErrorState = network::eErrorState::ERROR_CURL_INIT;
        }
        curl_global_cleanup();
    }

}
