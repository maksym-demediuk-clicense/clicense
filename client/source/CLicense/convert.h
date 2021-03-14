#pragma once

#include <iomanip>

using byte = unsigned char;

namespace convert
{
    std::string string_to_hex(const byte* buf, unsigned int size);
    std::string string_to_hex(const std::string& input);
    std::string hex_to_string(const byte* buf, unsigned int size);
    std::string hex_to_string(const std::string& input);

    template <class T, class S>
    std::string integral_to_abi(T value, S size)
    {
        static_assert(std::is_integral<T>::value, "Invalid usage of 'integral_to_bin'");

        std::stringstream ss;
        ss << std::right
            << std::setfill('0')
            << std::setw(size * 2)
            << std::hex << value;

        return ss.str();
    }

    std::string bytes_to_abi(const byte* value, unsigned int size);
    std::string bytes_to_abi(const std::string value);
    std::string length_to_bytes2(const std::size_t length);
    std::string string_to_fixed(const std::string& value, const std::size_t length);
}

