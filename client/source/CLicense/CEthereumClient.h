#pragma once

class CEthereumClient
{
public:
    CEthereumClient(const std::string& provider);
    ~CEthereumClient() = default;

    CEthereumClient* WithContract(IContract* contract);
    CEthereumClient* WithData(const std::string& data);
    CEthereumClient* WithData(const char* data, unsigned int size);

    std::string Call();
private:
    std::string m_strProvider;
    IContract* m_pContract;
    std::shared_ptr<std::string> m_strValidationJsonRpc;
};

