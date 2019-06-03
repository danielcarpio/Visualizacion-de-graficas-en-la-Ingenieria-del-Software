from django.shortcuts import render, redirect
from django.http import Http404
import requests

def index(request):
    return render(request, "base/index.html")

def getApi(url):
    try:
        jsonD = requests.get(url).json()
    except:
        raise Http404()

    if "message" in jsonD:
        if jsonD["message"] == "Not Found":
            return "private"
    return jsonD

def mainPage(request):
    dicc = {}

    if not "q" in request.GET:
        return redirect("/")
    elif request.GET["q"] == "":
        return redirect("/")
    else:
        name_project = request.GET["q"]

    jsonD = getApi("https://api.github.com/repos/" + name_project)

    if jsonD == "private":
        return redirect("/isPrivate/")

    dicc["title"] = name_project
    dicc["avatar"] = jsonD["owner"]["avatar_url"]
    dicc["owner"] = jsonD["owner"]["login"]
    dicc["stars"] = jsonD["stargazers_count"]
    dicc["watchers"] = jsonD["watchers_count"]
    dicc["issues"] = jsonD["open_issues_count"]
    dicc["wiki"] = jsonD["has_wiki"]
    dicc["projects"] = jsonD["has_projects"]
    if jsonD["license"] is None:
        dicc["license"] = "None"
    else:
        dicc["license"] = jsonD["license"]["name"]
    dicc["forks"] = jsonD["forks_count"]
    dicc["created_at"] = jsonD["created_at"][8:10] + "-" + jsonD["created_at"][5:7] + "-" + jsonD["created_at"][:4] + " " + jsonD["created_at"][11:-1]
    dicc["pushed_at"] = jsonD["pushed_at"][8:10] + "-" + jsonD["pushed_at"][5:7] + "-" + jsonD["pushed_at"][:4] + " " + jsonD["pushed_at"][11:-1]

    return render(request, "base/main.html", dicc)
