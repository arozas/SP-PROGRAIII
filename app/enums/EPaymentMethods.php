<?php

enum EPaymentMethods: int
{
    case CASH = 1;
    case DEBIT = 2;
    case CREDIT = 3;
    case BANKTRANSFER = 4;
}
