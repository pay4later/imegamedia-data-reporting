<?php

namespace Imega\DataReporting\Enums;

enum OrderJobType: int
{
    case NewInstall = 1;
    case MigrationUpgrade = 2;
    case OtherTask = 3;
    case BespokeDev = 4;
    case MultiLender = 5;
}
