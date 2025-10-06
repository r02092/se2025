FROM alpine
RUN <<EOS
apk --no-cache add git
git config --global safe.directory /workspace
wget -O- https://raw.githubusercontent.com/reviewdog/reviewdog/fd59714416d6d9a1c0692d872e38e7f8448df4fc/install.sh | sh -s -- -b /usr/local/bin v0.18.1
EOS
WORKDIR /workspace
