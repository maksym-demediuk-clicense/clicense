#define CRYPTOPP_ENABLE_NAMESPACE_WEAK 1
#include <iomanip>
#include <algorithm>
#include <stdexcept>

#include <md5.h>
#include <modes.h>
#include <aes.h>
#include <filters.h>
#include <hex.h>
#include <keccak.h>

#include "main.h"

const byte DEFAULT_AES_KEY[CryptoPP::AES::DEFAULT_KEYLENGTH + 1] = "\x6b\xb7\xdf\x9d\x71\x4d\xdd\x6b\x0e\x6f\xb9\x02\x91\xad\xce\xfe";

namespace crypt
{
    std::string Encrypt_AES(const std::string& input)
    {
        byte iv[CryptoPP::AES::BLOCKSIZE];
        memset(iv, 0x00, CryptoPP::AES::BLOCKSIZE);
        std::string encrypted;

        CryptoPP::AES::Encryption aesEncryption(DEFAULT_AES_KEY, CryptoPP::AES::DEFAULT_KEYLENGTH);
        CryptoPP::CBC_Mode_ExternalCipher::Encryption cbcEncryption(aesEncryption, iv);

        CryptoPP::StreamTransformationFilter stfEncryptor(cbcEncryption, new CryptoPP::StringSink(encrypted));
        stfEncryptor.Put(reinterpret_cast<const byte*>(input.c_str()), input.length());
        stfEncryptor.MessageEnd();

        return convert::string_to_hex(encrypted);
    }

    std::string Decrypt_AES(const std::string& input)
    {
        byte iv[CryptoPP::AES::BLOCKSIZE];
        memset(iv, 0x00, CryptoPP::AES::BLOCKSIZE);
        std::string decrypted = convert::hex_to_string(input);
        std::string plain;

        CryptoPP::AES::Decryption aesDecryption(DEFAULT_AES_KEY, CryptoPP::AES::DEFAULT_KEYLENGTH);
        CryptoPP::CBC_Mode_ExternalCipher::Decryption cbcDecryption(aesDecryption, iv);

        CryptoPP::StreamTransformationFilter stfDecryptor(cbcDecryption, new CryptoPP::StringSink(plain));
        stfDecryptor.Put(reinterpret_cast<const byte*>(decrypted.c_str()), decrypted.size());
        stfDecryptor.MessageEnd();

        return plain;
    }

    std::string Key_AES()
    {
        return convert::string_to_hex(DEFAULT_AES_KEY, CryptoPP::AES::DEFAULT_KEYLENGTH);
    }

    std::string Hash_MD5(const std::string& input)
    {
        byte digest[CryptoPP::Weak::MD5::DIGESTSIZE];

        CryptoPP::Weak::MD5 hash;
        hash.CalculateDigest(digest, reinterpret_cast<const byte*>(input.c_str()), input.length());

        return convert::string_to_hex(digest, sizeof(digest));
    }

    std::string Hash_Keccak256(const std::string& input)
    {
        byte digest[CryptoPP::Keccak_256::DIGESTSIZE];

        CryptoPP::Keccak_256 hash;
        hash.CalculateDigest(digest, reinterpret_cast<const byte*>(input.c_str()), input.length());

        return convert::string_to_hex(digest, sizeof(digest));
    }
}