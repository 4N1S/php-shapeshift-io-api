<?php

declare(strict_types=1);

namespace Achse\ShapeShiftIo;

interface Resources
{

    // GET

    const RATE = 'rate';
    const LIMIT = 'limit';
    const MARKET_INFO = 'marketinfo';
    const RECENT_TRANSACTIONS = 'recenttx';
    const RECENT_DEPOSIT_TRANSACTION_STATUS = 'txStat';
    const TIME_REMAINING = 'timeremaining';
    const LIST_OF_SUPPORTED_COINS = 'getcoins';
    const LIST_OF_TRANSACTIONS_WITH_API_KEY = 'txbyapikey';
    const LIST_OF_TRANSACTIONS_WITH_API_KEY_BY_ADDRESS = 'txbyaddress';
    const VALIDATE_ADDRESS = 'validateAddress';

    // POST

    const CREATE_TRANSACTION = 'shift';
    const REQUEST_RECEIPT = 'mail';
    const SEND_AMOUNT = 'sendamount';
    const CANCEL_PENDING_TRANSACTION = 'cancelpending';

}
