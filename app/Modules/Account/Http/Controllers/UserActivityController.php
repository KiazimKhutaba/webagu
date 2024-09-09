<?php

namespace App\Modules\Account\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Account\Dtos\UserActivityLogsFilterDto;
use App\Modules\Account\Repositories\UserActionsLoggingRepository;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Request as RequestAlias;

class UserActivityController extends Controller
{

    public function __construct(
        private readonly UserActionsLoggingRepository $loggingRepository
    )
    {
        $this->middleware('auth:api');
    }

    public function index(Request $request)
    {
        if($request->isMethod(RequestAlias::METHOD_POST)) {
            // Todo: add validation
            return $this->loggingRepository->getAllLogs(UserActivityLogsFilterDto::from($request->all()));
        }

        return $this->loggingRepository->getAllLogs();
    }

    public function getMostActiveUsers(): array
    {
        return $this->loggingRepository->getMosActiveUsers();
    }

    public function getUniqueIPs()
    {
        return $this->loggingRepository->getUniqueIPs();
    }

    public function getUniqueUrls()
    {
        return $this->loggingRepository->getUniqueUrls();
    }

    public function show(int $user_id)
    {
        return $this->loggingRepository->get($user_id);
    }

    public function getUsersIpInfo()
    {
        return $this->loggingRepository->getUsersIpInfo();
    }
}
