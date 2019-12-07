# pow-php-wasm-demo

This repository is proof of concept that implement proof of work system in PHP, javascript, and web assembly.

## Getting Started

Drop the files contained in this repository in server (example /var/www) and configure your server web with new virtual host with document root is the `htdocs` directory of this repository. It notice that want `settings.php` be locate outside document root.

## Hash function

Pass the values (in order) to SHA256 twice, like bitcoin

1. hostname - the hostname of your server `$_SERVER['HTTP_HOST']`
2. timestamp - a current iso-8601 timestamp in UTC
3. rand - the random string put by the server and stored to `$_SESSION['pow']['rand']`
4. counter - a nonce that is incremented until the hash satisfies the difficulty

## Prevent cheater customer

Cheater customer prevent by

1. Store difficulty in server variable of session PHP
2. Check customer specific random number in server variable of session PHP
3. Check solution use timestamp less than 1 old hour

## Developer check if customer solution solved?

Developer control if customer already enter valid solution to proof of work problem that uses `$_SESSION['pow']['solved']`

Example [htdocs/pow-walled-content.php](htdocs/pow-walled-content.php)

## Performance

This code execute 100-400x higher hash/sec compare pure javascript https://github.com/shenzhu2/pow-php-wasm-demo

This code execute 40% slower compare pure C. Remove I/O UI update (update_hashrate()) is more equal for C but bad UX.

## Build wasm

For generate `pow_wasm.js` and `pow_wasm.wasm` use `emscripten` sdk

```
# get emscripten sdk
cd
mkdir /tmp
cd /tmp
git clone https://github.com/emscripten-core/emsdk.git
cd emsdk
git pull
./emsdk install latest
./emsdk activate latest

# get this repo code
cd /tmp
git clone https://github.com/shenzhu2/pow-php-wasm-demo.git
cd pow-php-wasm-demo/src

# compile
source /tmp//emsdk/emsdk_env.sh
emcc get_pow_solution.c sha-256.c -s EXPORTED_FUNCTIONS='["_get_pow_solution", "_malloc", "_free"]' -s EXTRA_EXPORTED_RUNTIME_METHODS='["cwrap","getValue"]' -o pow_wasm.js -O3
```
## Troubleshoot

### MIME

If it achieve MIME the error in browser can need to add "Content-Type: application/wasm" for wasm file to web server

See https://emscripten.org/docs/compiling/WebAssembly.html?highlight=mime#web-server-setup

## Donate

If demo help you please make donation at monero address

```
4ATt62EMG6KGW6EnehvnJJABd75RavSxZY367JCb3QWzKZJzbjHexkuYQA3TwJznz1F8NgqzrgPKQ6vnxuYEpSYVMfuLEo9
```

Thank you!

## Authors

* **Shen Zhu <shenzhu@cock.li>** - 54BE 8C1D 9BC3 CD9A 554E  DD69 DA4C CB93 9EB8 AAD4 - https://github.com/shenzhu2
* **Alain Mosnier** - https://github.com/amosnier/sha-2
* **Chris Veness** - https://github.com/chrisveness/crypto

## License

This project is license with AGPL License. See [LICENSE](LICENSE)
