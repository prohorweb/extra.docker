# =============================================================================
# Local AI Coding Stack — Apple Silicon M4 Pro (24 GB)
# Бэкенд: MLX (Apple Silicon Optimized)
# =============================================================================

PORT ?= 8200
CTX ?= 4096

# Модель по умолчанию (ваша уже установленная)
MODEL ?= mlx-community/DeepSeek-Coder-V2-Lite-Instruct-4bit-AWQ

# Путь к вашему виртуальному окружению (проверено)
VENV_ACTIVATE := /Users/prohor/.venv/bin/activate

.PHONY: llm llm-bg llm-stop llm-status warmup logs clean help

# ----------------------------------------------------------------------
# Запуск в foreground (видимый режим)
# ----------------------------------------------------------------------
llm: llm-stop
	@echo "🚀 Запуск MLX сервера..."
	@echo "   Модель: $(MODEL)"
	@echo "   Порт: $(PORT) | Контекст: $(CTX)"
	@echo "   Виртуальное окружение: /Users/prohor/.venv"
	@echo ""
	@source $(VENV_ACTIVATE) && mlx_lm.server \
		--model $(MODEL) \
		--host 127.0.0.1 \
		--port $(PORT) \
		--max-tokens $(CTX)

# ----------------------------------------------------------------------
# Запуск в background (фоновый режим)
# ----------------------------------------------------------------------
llm-bg: llm-stop
	@echo "🚀 Запуск MLX сервера в фоне..."
	@echo "   Модель: $(MODEL)"
	@echo "   Порт: $(PORT) | Контекст: $(CTX)"
	@echo "   Виртуальное окружение: /Users/prohor/.venv"
	@mkdir -p logs
	@source $(VENV_ACTIVATE) && nohup mlx_lm.server \
		--model $(MODEL) \
		--host 127.0.0.1 \
		--port $(PORT) \
		--max-tokens $(CTX) \
		> logs/mlx.log 2>&1 &
	@echo "✅ Сервер запущен (PID: $$!)"
	@echo "📝 Логи: logs/mlx.log"

# ----------------------------------------------------------------------
# Остановка сервера
# ----------------------------------------------------------------------
llm-stop:
	@echo "🛑 Останавливаю MLX сервер..."
	@pkill -f "mlx_lm.server" && echo "   Сервер остановлен" || echo "   Сервер не был запущен"

# ----------------------------------------------------------------------
# Статус сервера
# ----------------------------------------------------------------------
llm-status:
	@pgrep -f "mlx_lm.server" > /dev/null && \
		echo "✅ MLX сервер запущен (порт: $(PORT))" || \
		echo "❌ MLX сервер не запущен"

# ----------------------------------------------------------------------
# Проверка, что venv работает
# ----------------------------------------------------------------------
check-venv:
	@echo "🔍 Проверка виртуального окружения..."
	@if [ -f $(VENV_ACTIVATE) ]; then \
		echo "✅ Виртуальное окружение найдено: $(VENV_ACTIVATE)"; \
		source $(VENV_ACTIVATE) && python -c "import mlx_lm; print('✅ mlx-lm установлен, версия:', mlx_lm.__version__)"; \
	else \
		echo "❌ Виртуальное окружение не найдено!"; \
		exit 1; \
	fi

# ----------------------------------------------------------------------
# Прогрев модели (отправка тестового запроса)
# ----------------------------------------------------------------------
warmup:
	@echo "🔥 Прогрев модели на порту $(PORT)..."
	@sleep 2
	@curl -s -X POST http://127.0.0.1:$(PORT)/v1/chat/completions \
		-H "Content-Type: application/json" \
		-d '{"model":"test","messages":[{"role":"user","content":"ping"}],"max_tokens":1}' > /dev/null 2>&1 && \
		echo "✅ Модель готова к работе" || \
		echo "⚠️ Сервер не отвечает. Запустите make llm или make llm-bg"

# ----------------------------------------------------------------------
# Просмотр последних логов
# ----------------------------------------------------------------------
logs:
	@if [ -f logs/mlx.log ]; then \
		echo "📋 Последние 20 строк логов:"; \
		echo "----------------------------------------"; \
		tail -20 logs/mlx.log; \
		echo "----------------------------------------"; \
	else \
		echo "❌ Логи не найдены. Запустите сервер (make llm-bg)"; \
	fi

# ----------------------------------------------------------------------
# Очистка логов
# ----------------------------------------------------------------------
clean:
	@rm -f logs/*.log
	@echo "🧹 Логи очищены"

# ----------------------------------------------------------------------
# Быстрый запуск с разными моделями
# ----------------------------------------------------------------------

# Запуск с DeepSeek (ваша основная модель)
llm-deepseek: MODEL := mlx-community/DeepSeek-Coder-V2-Lite-Instruct-4bit-AWQ
llm-deepseek: llm

# Запуск с Qwen2.5-Coder 14B (быстрая для кодинга)
llm-qwen14: MODEL := lmstudio-community/Qwen2.5-Coder-14B-Instruct-MLX-4bit
llm-qwen14: llm

# Запуск с Gemma 3 27B (универсальная для чата)
llm-gemma: MODEL := mlx-community/gemma-3-27b-it-qat-4bit
llm-gemma: llm

# Запуск с Qwen2.5-Coder 32B (тяжёлая, на грани)
llm-qwen32: MODEL := lmstudio-community/Qwen2.5-Coder-32B-Instruct-MLX-4bit
llm-qwen32: llm

# ----------------------------------------------------------------------
# Помощь
# ----------------------------------------------------------------------
help:
	@echo "╔══════════════════════════════════════════════════════════════╗"
	@echo "║     Local AI Coding Stack — Apple Silicon M4 Pro (24GB)     ║"
	@echo "║                    Бэкенд: MLX (Apple Silicon)               ║"
	@echo "╚══════════════════════════════════════════════════════════════╝"
	@echo ""
	@echo "📦 Основные команды:"
	@echo "  make llm          -> Запустить сервер в видимом режиме"
	@echo "  make llm-bg       -> Запустить сервер в фоне"
	@echo "  make llm-stop     -> Остановить сервер"
	@echo "  make llm-status   -> Проверить статус сервера"
	@echo "  make check-venv   -> Проверить работу виртуального окружения"
	@echo ""
	@echo "🔥 Специальные команды:"
	@echo "  make warmup       -> Отправить тестовый запрос (прогрев)"
	@echo "  make logs         -> Показать последние логи"
	@echo "  make clean        -> Очистить логи"
	@echo ""
	@echo "🤖 Быстрый запуск разных моделей:"
	@echo "  make llm-deepseek -> DeepSeek-Coder-V2 16B (кодинг, основная)"
	@echo "  make llm-qwen14   -> Qwen2.5-Coder 14B (очень быстрый кодинг)"
	@echo "  make llm-gemma    -> Gemma 3 27B (универсальная, чат)"
	@echo "  make llm-qwen32   -> Qwen2.5-Coder 32B (тяжёлая, на грани)"
	@echo ""
	@echo "⚙️ Текущая конфигурация:"
	@echo "  PORT=$(PORT)"
	@echo "  CTX=$(CTX) (размер контекста)"
	@echo "  MODEL=$(MODEL)"
	@echo "  VENV=/Users/prohor/.venv"
	@echo ""
	@echo "📝 Настройка Cline (VS Code):"
	@echo "  Provider: OpenAI Compatible"
	@echo "  Base URL: http://localhost:$(PORT)/v1"
	@echo "  API Key:  not-needed"
	@echo "  Model ID: $(MODEL)"