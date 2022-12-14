# very basic VPN HOOD token manager interface

# How to run : 
## Method 1) Run with the script: 

### Please make sure that you run the following code on the server and not on your own computer.
 
You can use the ./setup.sh script to do the work for you, 
otherwise you can follow the method 2 and run the docker manually. 
To use the ./setup.sh rund the following command in the exact 
order on the server. 

At the end of the setup script you'll get a link to the token manager interface.
Keep this URL for yourself and don't share your username and password.
 
`` mkdir /home/vpnhood``

`` cd /home/vpnhood ``

`` git clone https://github.com/timusup-team/vpnhoodui .``

``./setup.sh``

## Method 2) Running docker yourself (for advanced users)
### run the docker:

`` docker run --network host --volume $PWD/storage:/app/storage --name MyVpnHoodServer --env-file .env -d rkhalafiniya/vpnhoodui``

### Run php builtin server:

#### DON'T forget to add your server ip to the .env file!!!!
(!) The BASE_URL should start with / in the beginning.

``docker exec -d MyVpnHoodServer  bash /app/web/runui.sh``

After running the server you can access the interface through: 
[yourip]:8000/vpnhoodui


