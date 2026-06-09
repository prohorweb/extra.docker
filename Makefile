.PHONY: llm analyze index

llm:
	mlx_lm.server \
		--model lmstudio-community/Qwen3-14B-MLX-4bit \
		--host 127.0.0.1 \
		--port 8081

analyze:
	@echo "Ask question to AI (Continue)"

index:
	smgrep index .