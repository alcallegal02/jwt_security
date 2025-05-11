# kali.Dockerfile
FROM kalilinux/kali-rolling

# Argumento para la instalación no interactiva
ARG DEBIAN_FRONTEND=noninteractive

# Actualizar e instalar curl y mitmproxy
RUN apt-get update && \
    apt-get install -y --no-install-recommends \
    curl \
    mitmproxy \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Exponer el puerto del proxy (informativo, ya que accederemos vía 127.0.0.1 desde dentro)
EXPOSE 8080

# Ejecutar mitmdump (la versión de línea de comandos de mitmproxy)
# -p 8080: escuchar en el puerto 8080
# --set block_global=false: permite que mitmproxy reenvíe peticiones a Internet y a otras redes
#                         (importante para que pueda alcanzar tu servicio Apache)
# -q: modo silencioso (opcional, para menos output en la consola de mitmdump)
# Puedes quitar -q para ver más detalles del flujo en los logs del contenedor Kali.
CMD ["mitmdump", "-p", "8080", "--set", "block_global=false", "-q"]