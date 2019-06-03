FROM python:alpine
MAINTAINER danielcarpio

RUN apk add postgresql-dev gcc python3-dev musl-dev

WORKDIR /app

ADD . /app
RUN pip install -r requirements.txt

WORKDIR /app/analizeIt

RUN python3 manage.py migrate

CMD ["sh", "-c", "python3 manage.py runserver 0.0.0.0:8000"]