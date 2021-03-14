pragma solidity >= 0.5.0 < 0.7.0;

contract owned
{
    address owner;

    constructor() public
    {
        owner = msg.sender;
    }

    modifier onlyOwner
    {
        require(msg.sender == owner);
        _;
    }

    function transferOwnership(address newOwner) onlyOwner public
    {
        owner = newOwner;
    }
}

contract CLicense is owned
{
    uint constant LICENSE_HWID_LENGTH = 16;
    uint constant LICENSE_KEY_LENGTH = 8;
    uint constant LICENSE_VERSION_LENGTH = 32;
    uint constant LICENSE_RESPONSE_LENGTH = 4;
    
    enum LicenseState
    {
        NONE,
        ACTIVATED
    }

    struct LicenseInfo
    {
        uint timestamp;
        LicenseState state;
        bytes32 hwid;
    }

    string public Name = "CLicense";
    uint256 public Version = 1;

    LicenseInfo[] licenses;

    mapping(bytes8 => uint256) keyToLicenseIndex;

    event OnAddLicense(bytes8 key);
    event OnActivateLicense(bytes8 key);
    event OnDeleteLicense(bytes8 key);

    constructor(string memory _name, uint256 _version) public
    {
        Name = _name;
        Version = _version;
    }

    function updateVersion(uint256 _version) onlyOwner public
    {
        Version = _version;
    }

    function addLicense(bytes8 _key) onlyOwner public
    {
        LicenseInfo memory license = LicenseInfo({
            state : LicenseState.NONE,
            timestamp : now,
            hwid : 0x0
        });
        
        uint256 id = licenses.push(license);
        keyToLicenseIndex[_key] = id;
        
        emit OnAddLicense(_key);
    }

    function activateLicense(bytes8 _key, bytes16 _hwid) onlyOwner public
    {
        uint256 id = keyToLicenseIndex[_key] - 1;
        LicenseInfo storage license = licenses[id];
        require(license.timestamp != 0);
        require(license.state == LicenseState.NONE);

        license.state = LicenseState.ACTIVATED;
        license.hwid = _hwid;

        emit OnActivateLicense(_key);
    }

    function deleteLicense(bytes8 _key) onlyOwner public
    {
        uint256 id = keyToLicenseIndex[_key] - 1;
        delete licenses[id];
        delete keyToLicenseIndex[_key];
        
        emit OnDeleteLicense(_key);
    }

    function verifyLicense(bytes memory _data) public view returns(bytes4)
    {
        bytes4 result = 0x0;

        bytes32 version;
        bytes16 hwid;
        bytes8 key;

        uint256 offset = 32;
        
        assembly{ version := mload(add(_data, offset)) }
        offset += LICENSE_VERSION_LENGTH;
        
        assembly{ hwid := mload(add(_data, offset))}
        offset += LICENSE_HWID_LENGTH;
        
        assembly{ key := mload(add(_data, offset))}
        offset += LICENSE_KEY_LENGTH;
        
        uint response_index = 0;
        if (Version != uint256(version))
        {
            response_index = 1;
        }
        else
        {
            uint256 license_index = keyToLicenseIndex[key];

            if (license_index != 0)
            {
                LicenseInfo memory license = licenses[license_index - 1];
                if (license.timestamp != 0 && license.state == LicenseState.ACTIVATED)
                {
                    if (license.hwid == hwid)
                    {
                        response_index = 2;
                    }
                }
            }
        }
        
        offset += LICENSE_RESPONSE_LENGTH * response_index;
        assembly{ result := mload(add(_data, offset))}

        return result;
    }
    
    function LicenseCount() public view returns(uint256)
    {
        return licenses.length;
    }
}