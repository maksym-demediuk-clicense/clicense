#define NOMINMAX

#include <limits>

#include "main.h"

namespace convert
{
    std::string string_to_hex(const byte* buf, unsigned int size)
    {
        CryptoPP::HexEncoder encoder;
        std::string output;

        encoder.Attach(new CryptoPP::StringSink(output));
        encoder.Put(buf, size);
        encoder.MessageEnd();
        return output;
    }

    std::string string_to_hex(const std::string& input)
    {
        return string_to_hex(reinterpret_cast<const byte*>(input.c_str()), input.length());
    }

    std::string hex_to_string(const byte* buf, unsigned int size)
    {
        CryptoPP::HexDecoder decoder;
        std::string output;

        decoder.Attach(new CryptoPP::StringSink(output));
        decoder.Put(buf, size);
        decoder.MessageEnd();
        return output;
    }

    std::string hex_to_string(const std::string& input)
    {
        return hex_to_string(reinterpret_cast<const byte*>(input.c_str()), input.length());
    }

    std::string bytes_to_abi(const byte* value, unsigned int size)
    {
        std::stringstream ss;
        ss << integral_to_abi(size, 32) << value;

        auto pad = (32 - (size % 32)) << 1;
        if (pad)
        {
            ss << std::string(pad, '0');
        }

        return ss.str();
    }

    std::string bytes_to_abi(const std::string value)
    {
        return bytes_to_abi(reinterpret_cast<const byte*>(value.c_str()), value.length());
    }

    std::string length_to_bytes2(const std::size_t length)
    {
        const auto max_length = std::numeric_limits<unsigned short>::max();

        if (length > max_length)
        {
            return std::string();
        }

        std::stringstream ss;
        ss << std::right
            << std::setfill('0')
            << std::setw(sizeof(unsigned short) * 2)
            << std::hex
            << length;

        return ss.str();
    }

    std::string string_to_fixed(const std::string& value, const std::size_t length)
    {
        if (value.length() == length)
        {
            return value;
        }

        std::stringstream result;
        if (value.length() > length)
        {
            result << std::string(value.begin(), value.begin() + length);
        }
        else
        {
            result << std::right << std::setfill('0') << std::setw(length) << value;
        }

        return result.str();
    }

}
