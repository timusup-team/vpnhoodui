cd /app/web
if [ ! $USERNAME ] || [ ! $PASSWORD ]; then
    echo 'USERNAME OR PASSWORD IS NOT SET'
    exit
fi
if [ ! $BASE_URL ]; then
    echo 'BASE_URL Is not set, defaults to /vpnHood'
    export BASE_URL=/vpnHood
fi
if [ $HOST_PUBLIC_IP ]; then
    if [ $HOST_PORT ]; then
        echo 'Port is set to :' $HOST_PORT
        else
        echo 'NO PORT IS SET, Default 8000 will be used'
        export HOST_PORT=8000
    fi

    php -S "${HOST_PUBLIC_IP}:${HOST_PORT}"

  else
    echo 'NO PUBLIC IP SET'
    exit
fi

