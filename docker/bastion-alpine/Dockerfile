FROM alpine:latest AS bastion-build-alpine
RUN apk add --no-cache \
    binutils \
    socat && \
    apk del binutils

FROM alpine:latest AS bastion-alpine
# socatコマンドと必要なライブラリをコピー
COPY --from=bastion-build-alpine /usr/bin/socat /usr/bin/socat
COPY --from=bastion-build-alpine /lib /lib
COPY --from=bastion-build-alpine /usr/lib /usr/lib
COPY ./docker/bastion-alpine/entrypoint.sh /entrypoint.sh
COPY ./docker/bastion-alpine/proxypasslist.txt /proxypasslist.txt
RUN chmod +x /entrypoint.sh
ENTRYPOINT ["/bin/ash", "-c", "/entrypoint.sh"]
