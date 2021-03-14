#pragma once

class CLicenseContract : public IContract
{
public:
    CLicenseContract(const std::string&);
    virtual ~CLicenseContract();

    virtual std::shared_ptr<std::string> GetValidationJsonRpc(const char* data, unsigned int size) override;
    virtual std::string ParseResponse(const std::string& response) override;

private:
    std::string m_strContractAddress;
};

