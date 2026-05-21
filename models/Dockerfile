FROM python:3.10-slim

WORKDIR /app

# Install system dependencies
RUN apt-get update && apt-get install -y --no-install-recommends \
    build-essential \
    && rm -rf /var/lib/apt/lists/*

# Copy requirements and install
COPY requirements.txt .
RUN pip install --no-cache-dir -r requirements.txt

# Copy all model files
COPY . .

# Expose Hugging Face default port
EXPOSE 7860

# Start Flask on port 7860 and bind to 0.0.0.0
CMD ["python", "api.py"]
