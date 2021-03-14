#include "main.h"

#include <rapidjson/document.h>
#include <rapidjson/writer.h>
#include <rapidjson/stringbuffer.h>


CLicenseContract::CLicenseContract(const std::string& address)
    : m_strContractAddress(address)
{
}

CLicenseContract::~CLicenseContract()
{
}

std::shared_ptr<std::string> CLicenseContract::GetValidationJsonRpc(const char* data, unsigned int size)
{
    static auto jsonrpc = R"({"jsonrpc":"2.0","method":"eth_call","params": [{"to": "","data": ""}, "latest"],"id":1})";
    static auto method_hash = "b193bfd1";

    rapidjson::Document doc;
    doc.Parse(jsonrpc);

    auto& value_params = doc["params"].GetArray()[0].GetObject();

    rapidjson::Value& value_to = value_params["to"];
    value_to.SetString(rapidjson::StringRef(m_strContractAddress.c_str()));

    std::stringstream ss;
    ss << "0x" << method_hash;

    if (data && data[0] && size)
    {
        ss << convert::integral_to_abi(32, 32) << convert::bytes_to_abi(reinterpret_cast<const byte*>(data), size);
    }

    rapidjson::Value& value_data = value_params["data"];
    auto str = ss.str();
    value_data.SetString(str.c_str(), str.length(), doc.GetAllocator());

    rapidjson::StringBuffer sbuf;
    rapidjson::Writer<rapidjson::StringBuffer> writer(sbuf);
    doc.Accept(writer);

    return std::make_shared<std::string>(sbuf.GetString());
}

std::string CLicenseContract::ParseResponse(const std::string& response)
{
    rapidjson::Document doc;
    doc.Parse(response.c_str());

    auto result = (doc.HasMember("result")) ? doc["result"].GetString() : std::string();

    if (result.empty() == false)
    {
        auto start = result.begin();
        std::advance(start, 2);
        auto end = start;
        std::advance(end, 8);
        auto parsed = std::string(start, end);
        return parsed;
    }
    return result;
}
