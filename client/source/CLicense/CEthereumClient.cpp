#include "main.h"


CEthereumClient::CEthereumClient(const std::string& provider) : m_strProvider(provider)
{
}


CEthereumClient* CEthereumClient::WithContract(IContract* contract)
{
    m_pContract = contract;
    return this;
}

CEthereumClient* CEthereumClient::WithData(const char* data, unsigned int size)
{
    if (m_pContract == nullptr)
    {
        throw std::exception("Initialize contract first");
    }
    m_strValidationJsonRpc = m_pContract->GetValidationJsonRpc(data, size);
    return this;
}

CEthereumClient* CEthereumClient::WithData(const std::string& data)
{
    return WithData(data.c_str(), data.length() / 2);
}

std::string CEthereumClient::Call()
{
    auto response = network::SendHttpPost(m_strProvider, "Content-Type: application/json", *m_strValidationJsonRpc, true);
    return m_pContract->ParseResponse(response);
}
