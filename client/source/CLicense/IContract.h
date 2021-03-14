#include <memory>
#include <string>

class IContract
{
public:
    IContract() = default;
    virtual ~IContract() = default;

    virtual std::shared_ptr<std::string> GetValidationJsonRpc(const char* data, unsigned int size) = 0;
    virtual std::string ParseResponse(const std::string& response) = 0;
};