# Clone of pwa.group langing

## Development

> This project is deploted at `pwa.livich.top`

* `docker-compose up` (you'll need 80 and 3306 ports free)
* Inside container: `composer install`
* Adjust `config.php`: `TARGET_URL` is your destination (offer) URL, `DOMAIN_NAME` is PWA domain name. `SCOPE_SCHEME` is always `https`, PWAs won't work through HTTP!
* Go to desktop Chrome, open `chrome://inspect#devices`, connect your device via ADB and set up Port forwarding `80` (your development docker container) - `localhost:8000`
* Go to mobile Chrome, open `localhost:8000`. You'll be able to use web developer tools from desktop Chrome on this

## Query processing

The application provides different routes:

* `/analytic/[:id]` does application provisioning (see `/js/main.js` for details)
* `/analytic/[:id]/install` install event postback
* `/analytic/[:id]/push` push notifications status postback (WIP)
* `/analytic/[:id]/open` open event postback
* `/destination` implements simple destination page, just for testing

You may adjust all the interactions you need in `index.php`.
