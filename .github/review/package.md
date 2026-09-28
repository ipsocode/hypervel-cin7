This package, hypervel-cin7, is a Cin7 Core API client for Hypervel 0.4 (PHP 8.4+,
Swoole), built on hypervel/saloon.

In src/, in order:
1. The request that reaches Cin7: the endpoint path, the GUID key, query string versus
   JSON body, the page/limit defaults, and pagination, the rate limit and the 503 retry
   policy. The README's "Behavior inherited from eighteen73/dear-api" section is wire
   protocol: flag any change to it that the pull request does not call out as deliberate.
2. Coroutine safety: one Cin7Connector is a worker-lifetime singleton shared by every
   coroutine. New static or mutable state on it or on the requests; native
   sleep()/usleep() instead of the framework's Sleep and rate limiter; rate-limit or
   cooldown keys not scoped to the Cin7 account.
3. Transport: it stays on hypervel/saloon. The conventions check bans GuzzleHttp\ and
   curl_*; look for any other way around the connector's rate limiter and retry policy.
4. Tests: every send in the suite is faked through Saloon's mock client; nothing may reach
   the live Cin7 API.
5. Credentials: the Cin7 account ID and application key reaching logs, exception
   messages, fixtures or committed files.
