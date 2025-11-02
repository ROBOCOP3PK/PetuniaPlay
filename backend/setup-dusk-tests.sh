#!/bin/bash

# Script para configurar y ejecutar tests E2E con Laravel Dusk
# Uso: ./setup-dusk-tests.sh

echo "🧪 Configurando entorno para tests E2E (Dusk)"
echo "================================================"

# Colores para output
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

# 1. Verificar que ChromeDriver esté instalado
echo -e "\n${YELLOW}1. Verificando ChromeDriver...${NC}"
if command -v chromedriver &> /dev/null; then
    echo -e "${GREEN}✓ ChromeDriver instalado: $(chromedriver --version)${NC}"
else
    echo -e "${RED}✗ ChromeDriver no encontrado${NC}"
    echo "Instálalo con: sudo apt-get install chromium-chromedriver"
    echo "O descárgalo de: https://chromedriver.chromium.org/"
    exit 1
fi

# 2. Crear base de datos para Dusk
echo -e "\n${YELLOW}2. Creando base de datos para tests...${NC}"
mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS petunia_play_dusk CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;" 2>/dev/null
if [ $? -eq 0 ]; then
    echo -e "${GREEN}✓ Base de datos 'petunia_play_dusk' creada/verificada${NC}"

    # Otorgar permisos
    mysql -u root -p -e "GRANT ALL PRIVILEGES ON petunia_play_dusk.* TO 'petunia_user'@'localhost';" 2>/dev/null
    mysql -u root -p -e "FLUSH PRIVILEGES;" 2>/dev/null
    echo -e "${GREEN}✓ Permisos otorgados a petunia_user${NC}"
else
    echo -e "${RED}✗ Error al crear base de datos${NC}"
    echo "Ejecuta manualmente:"
    echo "  mysql -u root -p"
    echo "  CREATE DATABASE petunia_play_dusk;"
    echo "  GRANT ALL PRIVILEGES ON petunia_play_dusk.* TO 'petunia_user'@'localhost';"
    exit 1
fi

# 3. Verificar que el archivo .env.dusk.local existe
echo -e "\n${YELLOW}3. Verificando configuración de Dusk...${NC}"
if [ -f ".env.dusk.local" ]; then
    echo -e "${GREEN}✓ Archivo .env.dusk.local encontrado${NC}"
else
    echo -e "${RED}✗ Archivo .env.dusk.local no encontrado${NC}"
    exit 1
fi

# 4. Ejecutar migraciones en la base de datos de testing
echo -e "\n${YELLOW}4. Ejecutando migraciones en BD de testing...${NC}"
php artisan migrate:fresh --database=mysql --env=dusk.local --force
if [ $? -eq 0 ]; then
    echo -e "${GREEN}✓ Migraciones ejecutadas${NC}"
else
    echo -e "${RED}✗ Error al ejecutar migraciones${NC}"
    exit 1
fi

# 5. Ejecutar seeders
echo -e "\n${YELLOW}5. Ejecutando seeders...${NC}"
php artisan db:seed --env=dusk.local --force
if [ $? -eq 0 ]; then
    echo -e "${GREEN}✓ Seeders ejecutados${NC}"
else
    echo -e "${RED}✗ Error al ejecutar seeders${NC}"
    exit 1
fi

# 6. Verificar que el frontend esté corriendo
echo -e "\n${YELLOW}6. Verificando servicios...${NC}"
if curl -s http://localhost:5173 > /dev/null; then
    echo -e "${GREEN}✓ Frontend activo en http://localhost:5173${NC}"
else
    echo -e "${RED}✗ Frontend no está corriendo${NC}"
    echo "Inicia el frontend con: cd ../frontend && npm run dev"
fi

if curl -s http://localhost:8000 > /dev/null; then
    echo -e "${GREEN}✓ Backend activo en http://localhost:8000${NC}"
else
    echo -e "${YELLOW}! Backend no está corriendo${NC}"
    echo "Inicia el backend con: php artisan serve"
fi

# 7. Preguntar qué tests ejecutar
echo -e "\n${YELLOW}7. ¿Qué tests deseas ejecutar?${NC}"
echo "1) Todos los tests E2E"
echo "2) AuthenticationTest"
echo "3) ProductManagementTest"
echo "4) LoyaltySystemTest"
echo "5) CheckoutFlowTest"
echo "6) Solo configurar (no ejecutar tests)"
read -p "Selecciona opción (1-6): " option

case $option in
    1)
        echo -e "\n${YELLOW}Ejecutando TODOS los tests E2E...${NC}"
        php artisan dusk
        ;;
    2)
        echo -e "\n${YELLOW}Ejecutando AuthenticationTest...${NC}"
        php artisan dusk --filter=AuthenticationTest
        ;;
    3)
        echo -e "\n${YELLOW}Ejecutando ProductManagementTest...${NC}"
        php artisan dusk --filter=ProductManagementTest
        ;;
    4)
        echo -e "\n${YELLOW}Ejecutando LoyaltySystemTest...${NC}"
        php artisan dusk --filter=LoyaltySystemTest
        ;;
    5)
        echo -e "\n${YELLOW}Ejecutando CheckoutFlowTest...${NC}"
        php artisan dusk --filter=CheckoutFlowTest
        ;;
    6)
        echo -e "\n${GREEN}Configuración completada. Para ejecutar tests manualmente:${NC}"
        echo "  php artisan dusk"
        echo "  php artisan dusk --filter=TestName"
        exit 0
        ;;
    *)
        echo -e "${RED}Opción inválida${NC}"
        exit 1
        ;;
esac

# 8. Mostrar resultados
echo -e "\n${GREEN}================================================${NC}"
echo -e "${GREEN}✓ Tests completados${NC}"
echo -e "${GREEN}================================================${NC}"
echo ""
echo "📊 Para ver screenshots de errores:"
echo "   ls tests/Browser/screenshots/"
echo ""
echo "📝 Para ver logs de consola:"
echo "   ls tests/Browser/console/"
echo ""
echo "🔄 Para ejecutar tests específicos:"
echo "   php artisan dusk --filter=test_nombre_del_test"
echo ""
