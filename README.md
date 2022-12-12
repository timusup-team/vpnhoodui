# very basic VPN HOOD token manager interface

## run the docker:

`` docker run --network host --volume /home/VpnHoodServer/storage:/app/storage --name MyVpnHoodServer --env-file .env -d rkhalafiniya/vpnhoodui:0.4``

## Run php builtin server:

#### DON'T forget to add your server ip to the .env file!!!!
(!) The BASE_URL should start with / in the beginning.

``docker exec -d MyVpnHoodServer  bash /app/web/runui.sh``

After running the server you can access the interface through: 
[yourip]:8000/vpnhoodui


