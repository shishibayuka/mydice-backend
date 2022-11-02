#!/bin/ash

#設定ファイルから変数を取得
PROXY_HOSTS="`cat ./proxypasslist.txt`"

#ループで一要素ずつsocatを実行
COUNT=0
LEN=`echo "${PROXY_HOSTS}" | wc -l`
for proxy_host in `echo ${PROXY_HOSTS}`
do
    COUNT=`expr $COUNT + 1`
    local_port=`echo ${proxy_host} | cut -f 1 -d ":"`
    host=`echo ${proxy_host} | cut -f 2 -d ":"`
    remote_port=`echo ${proxy_host} | cut -f 3 -d ":"`
    if test ${COUNT} -ge ${LEN} ; then
    #最後のジョブのみフォアグランドで実行
        socat tcp-listen:${local_port},reuseaddr,fork TCP:${host}:${remote_port}
    else
        socat tcp-listen:${local_port},reuseaddr,fork TCP:${host}:${remote_port} &
    fi
done