FROM mysql:latest

# Включити TLSv1.2
RUN set -eux; \
    test -z $(grep "[[:blank:]]*tls_version[[:blank:]]*=[v0-9 \.\,]*" /etc/mysql/my.cnf) && \
        sed -i "s/\[mysqld\]/\[mysqld\]\ntls_version=TLSv1\.2\n/g" /etc/mysql/my.cnf; \
    test -z $(grep "[[:blank:]]*tls_version[[:blank:]]*=[v0-9 \.\,]*" /etc/mysql/my.cnf) || \
        sed -i "s/\*tls_version[[:blank:]]*=[v0-9 \.\,]*/tls_version=TLSv1\.2/g" /etc/mysql/my.cnf