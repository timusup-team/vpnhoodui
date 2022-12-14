# very basic VPN HOOD token manager interface

## Run with the script: 

### Please make sure that you run the following code on the server and not on your own computer.
 
You can run the ./setup.sh script to do the work for you, otherwise you can follow the following examples to run the docker.
 
`` mkdir /home/vpnhood``

`` cd /home/vpnhood ``

`` git clone https://github.com/timusup-team/vpnhoodui .``

``./web/setup.sh``
## run the docker:

`` docker run --network host --volume $PWD/storage:/app/storage --name MyVpnHoodServer --env-file .env -d rkhalafiniya/vpnhoodui``

## Run php builtin server:

#### DON'T forget to add your server ip to the .env file!!!!
(!) The BASE_URL should start with / in the beginning.

``docker exec -d MyVpnHoodServer  bash /app/web/runui.sh``

After running the server you can access the interface through: 
[yourip]:8000/vpnhoodui


