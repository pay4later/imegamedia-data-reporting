<?php

namespace Imega\DataReporting\Enums;

enum OrderStatus: int
{
    case AwaitingAccountInfo = 1;
    case DetailsReceived = 2;
    case ScheduledAndWaiting = 3;
    case InstallationInProgress = 4;
    case DetailsIncompleteNotWorking = 5;
    case OnHoldLongTerm = 6;
    case AwaitingTestingDocumentation = 7;
    case AwaitingSecondApproval = 8;
    case HoldingPlaceForReleaseToProduction = 9;
    case FinalClearupTasks = 10;
    case Active = 11;
    case Cancelled = 12;
    case Misc = 13;
    case HoldingAreaForDevelopmentTasks = 14;
    case DekoDirectIntegrations = 15;
}
