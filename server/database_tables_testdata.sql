-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Хост: localhost:3306
-- Час створення: Бер 14 2021 р., 01:26
-- Версія сервера: 10.3.16-MariaDB
-- Версія PHP: 7.3.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База даних: `id16289348_clicense_db`
--

-- --------------------------------------------------------

--
-- Структура таблиці `db_eth_accounts`
--

CREATE TABLE `db_eth_accounts` (
  `id` int(11) NOT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `pkey` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп даних таблиці `db_eth_accounts`
--

INSERT INTO `db_eth_accounts` (`id`, `address`, `pkey`) VALUES
(1, '0xdeECE9C83Dd2FbcEe6503B80582946e4972081a0', 'ed9c452aecc94fbdeb6bad08215459574aca75001523fe5be7a8e686fce900c0');

-- --------------------------------------------------------

--
-- Структура таблиці `db_eth_contracts`
--

CREATE TABLE `db_eth_contracts` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `abi` text COLLATE utf8_unicode_ci NOT NULL,
  `bytecode` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп даних таблиці `db_eth_contracts`
--

INSERT INTO `db_eth_contracts` (`id`, `name`, `abi`, `bytecode`) VALUES
(1, 'CLicense', '[\r\n	{\r\n		\"constant\": false,\r\n		\"inputs\": [\r\n			{\r\n				\"name\": \"_key\",\r\n				\"type\": \"bytes8\"\r\n			},\r\n			{\r\n				\"name\": \"_hwid\",\r\n				\"type\": \"bytes16\"\r\n			}\r\n		],\r\n		\"name\": \"activateLicense\",\r\n		\"outputs\": [],\r\n		\"payable\": false,\r\n		\"stateMutability\": \"nonpayable\",\r\n		\"type\": \"function\"\r\n	},\r\n	{\r\n		\"constant\": false,\r\n		\"inputs\": [\r\n			{\r\n				\"name\": \"_key\",\r\n				\"type\": \"bytes8\"\r\n			}\r\n		],\r\n		\"name\": \"addLicense\",\r\n		\"outputs\": [],\r\n		\"payable\": false,\r\n		\"stateMutability\": \"nonpayable\",\r\n		\"type\": \"function\"\r\n	},\r\n	{\r\n		\"constant\": false,\r\n		\"inputs\": [\r\n			{\r\n				\"name\": \"_key\",\r\n				\"type\": \"bytes8\"\r\n			}\r\n		],\r\n		\"name\": \"deleteLicense\",\r\n		\"outputs\": [],\r\n		\"payable\": false,\r\n		\"stateMutability\": \"nonpayable\",\r\n		\"type\": \"function\"\r\n	},\r\n	{\r\n		\"constant\": false,\r\n		\"inputs\": [\r\n			{\r\n				\"name\": \"newOwner\",\r\n				\"type\": \"address\"\r\n			}\r\n		],\r\n		\"name\": \"transferOwnership\",\r\n		\"outputs\": [],\r\n		\"payable\": false,\r\n		\"stateMutability\": \"nonpayable\",\r\n		\"type\": \"function\"\r\n	},\r\n	{\r\n		\"constant\": false,\r\n		\"inputs\": [\r\n			{\r\n				\"name\": \"_version\",\r\n				\"type\": \"uint256\"\r\n			}\r\n		],\r\n		\"name\": \"updateVersion\",\r\n		\"outputs\": [],\r\n		\"payable\": false,\r\n		\"stateMutability\": \"nonpayable\",\r\n		\"type\": \"function\"\r\n	},\r\n	{\r\n		\"inputs\": [\r\n			{\r\n				\"name\": \"_name\",\r\n				\"type\": \"string\"\r\n			},\r\n			{\r\n				\"name\": \"_version\",\r\n				\"type\": \"uint256\"\r\n			}\r\n		],\r\n		\"payable\": false,\r\n		\"stateMutability\": \"nonpayable\",\r\n		\"type\": \"constructor\"\r\n	},\r\n	{\r\n		\"anonymous\": false,\r\n		\"inputs\": [\r\n			{\r\n				\"indexed\": false,\r\n				\"name\": \"key\",\r\n				\"type\": \"bytes8\"\r\n			}\r\n		],\r\n		\"name\": \"OnAddLicense\",\r\n		\"type\": \"event\"\r\n	},\r\n	{\r\n		\"anonymous\": false,\r\n		\"inputs\": [\r\n			{\r\n				\"indexed\": false,\r\n				\"name\": \"key\",\r\n				\"type\": \"bytes8\"\r\n			}\r\n		],\r\n		\"name\": \"OnActivateLicense\",\r\n		\"type\": \"event\"\r\n	},\r\n	{\r\n		\"anonymous\": false,\r\n		\"inputs\": [\r\n			{\r\n				\"indexed\": false,\r\n				\"name\": \"key\",\r\n				\"type\": \"bytes8\"\r\n			}\r\n		],\r\n		\"name\": \"OnDeleteLicense\",\r\n		\"type\": \"event\"\r\n	},\r\n	{\r\n		\"constant\": true,\r\n		\"inputs\": [],\r\n		\"name\": \"LicenseCount\",\r\n		\"outputs\": [\r\n			{\r\n				\"name\": \"\",\r\n				\"type\": \"uint256\"\r\n			}\r\n		],\r\n		\"payable\": false,\r\n		\"stateMutability\": \"view\",\r\n		\"type\": \"function\"\r\n	},\r\n	{\r\n		\"constant\": true,\r\n		\"inputs\": [],\r\n		\"name\": \"Name\",\r\n		\"outputs\": [\r\n			{\r\n				\"name\": \"\",\r\n				\"type\": \"string\"\r\n			}\r\n		],\r\n		\"payable\": false,\r\n		\"stateMutability\": \"view\",\r\n		\"type\": \"function\"\r\n	},\r\n	{\r\n		\"constant\": true,\r\n		\"inputs\": [\r\n			{\r\n				\"name\": \"_data\",\r\n				\"type\": \"bytes\"\r\n			}\r\n		],\r\n		\"name\": \"verifyLicense\",\r\n		\"outputs\": [\r\n			{\r\n				\"name\": \"\",\r\n				\"type\": \"bytes4\"\r\n			}\r\n		],\r\n		\"payable\": false,\r\n		\"stateMutability\": \"view\",\r\n		\"type\": \"function\"\r\n	},\r\n	{\r\n		\"constant\": true,\r\n		\"inputs\": [],\r\n		\"name\": \"Version\",\r\n		\"outputs\": [\r\n			{\r\n				\"name\": \"\",\r\n				\"type\": \"uint256\"\r\n			}\r\n		],\r\n		\"payable\": false,\r\n		\"stateMutability\": \"view\",\r\n		\"type\": \"function\"\r\n	}\r\n]', '60806040526040518060400160405280600881526020017f434c6963656e7365000000000000000000000000000000000000000000000000815250600190805190602001906200005192919062000155565b5060016002553480156200006457600080fd5b5060405162000ead38038062000ead833981018060405260408110156200008a57600080fd5b810190808051640100000000811115620000a357600080fd5b82810190506020810184811115620000ba57600080fd5b8151856001820283011164010000000082111715620000d857600080fd5b505092919060200180519060200190929190505050336000806101000a81548173ffffffffffffffffffffffffffffffffffffffff021916908373ffffffffffffffffffffffffffffffffffffffff16021790555081600190805190602001906200014592919062000155565b5080600281905550505062000204565b828054600181600116156101000203166002900490600052602060002090601f016020900481019282601f106200019857805160ff1916838001178555620001c9565b82800160010185558215620001c9579182015b82811115620001c8578251825591602001919060010190620001ab565b5b509050620001d89190620001dc565b5090565b6200020191905b80821115620001fd576000816000905550600101620001e3565b5090565b90565b610c9980620002146000396000f3fe608060405234801561001057600080fd5b50600436106100935760003560e01c8063b193bfd111610066578063b193bfd1146101b0578063bb62860d146102bd578063cf705123146102db578063d8e4694614610341578063f2fde38b1461038a57610093565b80638052474d14610098578063970f86f31461011b5780639a00166114610164578063a87f884e14610182575b600080fd5b6100a06103ce565b6040518080602001828103825283818151815260200191508051906020019080838360005b838110156100e05780820151818401526020810190506100c5565b50505050905090810190601f16801561010d5780820380516001836020036101000a031916815260200191505b509250505060405180910390f35b6101626004803603602081101561013157600080fd5b81019080803577ffffffffffffffffffffffffffffffffffffffffffffffff1916906020019092919050505061046c565b005b61016c610616565b6040518082815260200191505060405180910390f35b6101ae6004803603602081101561019857600080fd5b8101908080359060200190929190505050610623565b005b610269600480360360208110156101c657600080fd5b81019080803590602001906401000000008111156101e357600080fd5b8201836020820111156101f557600080fd5b8035906020019184600183028401116401000000008311171561021757600080fd5b91908080601f016020809104026020016040519081016040528093929190818152602001838380828437600081840152601f19601f820116905080830192505050505050509192919290505050610686565b60405180827bffffffffffffffffffffffffffffffffffffffffffffffffffffffff19167bffffffffffffffffffffffffffffffffffffffffffffffffffffffff1916815260200191505060405180910390f35b6102c561081a565b6040518082815260200191505060405180910390f35b61033f600480360360408110156102f157600080fd5b81019080803577ffffffffffffffffffffffffffffffffffffffffffffffff1916906020019092919080356fffffffffffffffffffffffffffffffff19169060200190929190505050610820565b005b6103886004803603602081101561035757600080fd5b81019080803577ffffffffffffffffffffffffffffffffffffffffffffffff191690602001909291905050506109e4565b005b6103cc600480360360208110156103a057600080fd5b81019080803573ffffffffffffffffffffffffffffffffffffffff169060200190929190505050610ba2565b005b60018054600181600116156101000203166002900480601f0160208091040260200160405190810160405280929190818152602001828054600181600116156101000203166002900480156104645780601f1061043957610100808354040283529160200191610464565b820191906000526020600020905b81548152906001019060200180831161044757829003601f168201915b505050505081565b6000809054906101000a900473ffffffffffffffffffffffffffffffffffffffff1673ffffffffffffffffffffffffffffffffffffffff163373ffffffffffffffffffffffffffffffffffffffff16146104c557600080fd5b60006001600460008477ffffffffffffffffffffffffffffffffffffffffffffffff191677ffffffffffffffffffffffffffffffffffffffffffffffff19168152602001908152602001600020540390506003818154811061052357fe5b90600052602060002090600302016000808201600090556001820160006101000a81549060ff021916905560028201600090555050600460008377ffffffffffffffffffffffffffffffffffffffffffffffff191677ffffffffffffffffffffffffffffffffffffffffffffffff19168152602001908152602001600020600090557ff0e8452bce607440bd0d9f1d03dd2f1208a5e87540762ac666ce2fa874e31c2982604051808277ffffffffffffffffffffffffffffffffffffffffffffffff191677ffffffffffffffffffffffffffffffffffffffffffffffff1916815260200191505060405180910390a15050565b6000600380549050905090565b6000809054906101000a900473ffffffffffffffffffffffffffffffffffffffff1673ffffffffffffffffffffffffffffffffffffffff163373ffffffffffffffffffffffffffffffffffffffff161461067c57600080fd5b8060028190555050565b600080600060e01b90506000806000806020905080870151935060208101905080870151925060108101905080870151915060088101905060008090508460001c600254146106d857600190506107fe565b6000600460008577ffffffffffffffffffffffffffffffffffffffffffffffff191677ffffffffffffffffffffffffffffffffffffffffffffffff19168152602001908152602001600020549050600081146107fc57610736610c3e565b6003600183038154811061074657fe5b9060005260206000209060030201604051806060016040529081600082015481526020016001820160009054906101000a900460ff16600181111561078757fe5b600181111561079257fe5b8152602001600282015481525050905060008160000151141580156107d057506001808111156107be57fe5b816020015160018111156107ce57fe5b145b156107fa57856fffffffffffffffffffffffffffffffff1916816040015114156107f957600292505b5b505b505b8060040282019150818801519550859650505050505050919050565b60025481565b6000809054906101000a900473ffffffffffffffffffffffffffffffffffffffff1673ffffffffffffffffffffffffffffffffffffffff163373ffffffffffffffffffffffffffffffffffffffff161461087957600080fd5b60006001600460008577ffffffffffffffffffffffffffffffffffffffffffffffff191677ffffffffffffffffffffffffffffffffffffffffffffffff19168152602001908152602001600020540390506000600382815481106108d957fe5b906000526020600020906003020190506000816000015414156108fb57600080fd5b6000600181111561090857fe5b8160010160009054906101000a900460ff16600181111561092557fe5b1461092f57600080fd5b60018160010160006101000a81548160ff0219169083600181111561095057fe5b0217905550826fffffffffffffffffffffffffffffffff191681600201819055507f361a973ec94449377321b98245e9450372955546882a867e7ae663f7a76bba4684604051808277ffffffffffffffffffffffffffffffffffffffffffffffff191677ffffffffffffffffffffffffffffffffffffffffffffffff1916815260200191505060405180910390a150505050565b6000809054906101000a900473ffffffffffffffffffffffffffffffffffffffff1673ffffffffffffffffffffffffffffffffffffffff163373ffffffffffffffffffffffffffffffffffffffff1614610a3d57600080fd5b610a45610c3e565b604051806060016040528042815260200160006001811115610a6357fe5b81526020016000801b81525090506000600382908060018154018082558091505090600182039060005260206000209060030201600090919290919091506000820151816000015560208201518160010160006101000a81548160ff02191690836001811115610acf57fe5b0217905550604082015181600201555050905080600460008577ffffffffffffffffffffffffffffffffffffffffffffffff191677ffffffffffffffffffffffffffffffffffffffffffffffff19168152602001908152602001600020819055507f095d3f6861e7e79e62c503e16a46d064ac7e31b3119de525b01b6fb0e0a2dc1b83604051808277ffffffffffffffffffffffffffffffffffffffffffffffff191677ffffffffffffffffffffffffffffffffffffffffffffffff1916815260200191505060405180910390a1505050565b6000809054906101000a900473ffffffffffffffffffffffffffffffffffffffff1673ffffffffffffffffffffffffffffffffffffffff163373ffffffffffffffffffffffffffffffffffffffff1614610bfb57600080fd5b806000806101000a81548173ffffffffffffffffffffffffffffffffffffffff021916908373ffffffffffffffffffffffffffffffffffffffff16021790555050565b60405180606001604052806000815260200160006001811115610c5d57fe5b815260200160008019168152509056fea165627a7a723058206c876ea1af9404f0c352d2bb2d6678f40c8eaef6cc1b5d64eb1715c7c15503ee0029');

-- --------------------------------------------------------

--
-- Структура таблиці `db_eth_providers`
--

CREATE TABLE `db_eth_providers` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `host` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `chain_id` int(11) NOT NULL,
  `tx_info_api` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп даних таблиці `db_eth_providers`
--

INSERT INTO `db_eth_providers` (`id`, `name`, `host`, `chain_id`, `tx_info_api`) VALUES
(1, '[infura.io] Ropsten', 'https://ropsten.infura.io/v3/a1136578201c43739c6aac15569ff664', 3, 'https://ropsten.etherscan.io/'),
(2, '[infura.io] Kovan', 'https://kovan.infura.io/v3/a1136578201c43739c6aac15569ff664', 42, 'https://kovan.etherscan.io/');

-- --------------------------------------------------------

--
-- Структура таблиці `db_licenses`
--

CREATE TABLE `db_licenses` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `public_key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `private_key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `transaction_add` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `transaction_activate` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `activated` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп даних таблиці `db_licenses`
--

INSERT INTO `db_licenses` (`id`, `product_id`, `email`, `public_key`, `private_key`, `transaction_add`, `transaction_activate`, `activated`) VALUES
(13, 1, 'max.demediuk@gmail.com', '304573341bdb3c20', 'd897a463d3060f5c', '0x9f7783d8049a77d14ffd8f657ef97ac765e412a64dafc010e19263fad16ed6d9', NULL, 0),
(14, 1, 'max.demediuk@gmail.com', '8ab4932765b6dcd3', '508a11220fc25b42', '0x3c86240394ff0000647a293b2dda9f948ad86329bfaf268a6222c52e4518d537', '0xc7ee6c6f517463176a3646341f46fc8b2ae016583ea3107ccf93a3817646c980', 1);

-- --------------------------------------------------------

--
-- Структура таблиці `db_products`
--

CREATE TABLE `db_products` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `contract_id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `provider_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `version` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `transaction_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `contract_address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sync_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп даних таблиці `db_products`
--

INSERT INTO `db_products` (`id`, `user_id`, `contract_id`, `account_id`, `provider_id`, `name`, `version`, `transaction_id`, `contract_address`, `sync_date`) VALUES
(1, 1, 1, 1, 1, 'CLicense', '5', '0x325dd60bdf76a02a106a37323c87ebd8e6d02e8ae65a5d9ce6a3a3379e9ab79c', '0x8e4d8048f8016fedf1e6c1dde553108612d0456d', '2021-03-11 08:05:16');

-- --------------------------------------------------------

--
-- Структура таблиці `db_users`
--

CREATE TABLE `db_users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `active` bit(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп даних таблиці `db_users`
--

INSERT INTO `db_users` (`id`, `username`, `password`, `active`) VALUES
(1, 'admin', 'd41d8cd98f00b204e9800998ecf8427e', b'1');

--
-- Індекси збережених таблиць
--

--
-- Індекси таблиці `db_eth_accounts`
--
ALTER TABLE `db_eth_accounts`
  ADD PRIMARY KEY (`id`);

--
-- Індекси таблиці `db_eth_contracts`
--
ALTER TABLE `db_eth_contracts`
  ADD PRIMARY KEY (`id`);

--
-- Індекси таблиці `db_eth_providers`
--
ALTER TABLE `db_eth_providers`
  ADD PRIMARY KEY (`id`);

--
-- Індекси таблиці `db_licenses`
--
ALTER TABLE `db_licenses`
  ADD PRIMARY KEY (`id`);

--
-- Індекси таблиці `db_products`
--
ALTER TABLE `db_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `contract_id` (`contract_id`),
  ADD KEY `provider_id` (`provider_id`),
  ADD KEY `account_id` (`account_id`);

--
-- Індекси таблиці `db_users`
--
ALTER TABLE `db_users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для збережених таблиць
--

--
-- AUTO_INCREMENT для таблиці `db_eth_accounts`
--
ALTER TABLE `db_eth_accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблиці `db_eth_contracts`
--
ALTER TABLE `db_eth_contracts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблиці `db_eth_providers`
--
ALTER TABLE `db_eth_providers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблиці `db_licenses`
--
ALTER TABLE `db_licenses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT для таблиці `db_products`
--
ALTER TABLE `db_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблиці `db_users`
--
ALTER TABLE `db_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Обмеження зовнішнього ключа збережених таблиць
--

--
-- Обмеження зовнішнього ключа таблиці `db_products`
--
ALTER TABLE `db_products`
  ADD CONSTRAINT `db_products_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `db_users` (`id`),
  ADD CONSTRAINT `db_products_ibfk_2` FOREIGN KEY (`contract_id`) REFERENCES `db_eth_contracts` (`id`),
  ADD CONSTRAINT `db_products_ibfk_3` FOREIGN KEY (`provider_id`) REFERENCES `db_eth_providers` (`id`),
  ADD CONSTRAINT `db_products_ibfk_4` FOREIGN KEY (`account_id`) REFERENCES `db_eth_accounts` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
