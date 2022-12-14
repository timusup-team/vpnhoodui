whoami
DOCKER_PATH=$(which docker)
function create_env_file(){
    echo "BASE_URL=${PATH}
USERNAME=${Username}
PASSWORD=${Password}
HOST_PUBLIC_IP=${SERVER_IP}
HOST_PORT=${PORT}" > .env
}

echo 'What is the server IP:'
read SERVER_IP

if [ ! $SERVER_IP ]; then
    echo 'SERVER ip is mandatory'
    exit
fi

echo 'What port should be used (default 8000):'
read PORT
if [ ! $PORT ]; then
  PORT=8000
fi


echo 'What should the path look like. It should start with / (default /vpnhoodui): '
read PATH
if [ ! $PATH ]; then
  PATH='/vpnhoodui'
fi


echo 'What is the Username (default admin): '
read Username
if [ ! $Username ]; then
  Username='admin'
fi

echo 'What is the Password (default admin): '
read Password
if [ ! $Password ]; then
  Password='admin'
fi

create_env_file

$DOCKER_PATH run --network host --volume $PWD:/app/storage --name MyVpnHoodServer --env-file .env -d rkhalafiniya/vpnhoodui

$DOCKER_PATH cp ./. MyVpnHoodServer:/app/web

$DOCKER_PATH exec -d MyVpnHoodServer  bash /app/web/runui.sh

echo "You can access the Control panel through : http://${SERVER_IP}:${PORT}${PATH}"
echo "Username: ${Username}  Password: ${Password}"


