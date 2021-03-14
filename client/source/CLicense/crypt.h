#pragma once

namespace crypt
{
    std::string Encrypt_AES(const std::string& input);
    std::string Decrypt_AES(const std::string& input);
    std::string Key_AES();
    std::string Hash_MD5(const std::string& input);
    std::string Hash_Keccak256(const std::string& input);
}
