# GitHub MCP Setup Guide - BookShare Project

## Schritt 1: GitHub Repository erstellen

1. Gehen Sie zu [GitHub.com](https://github.com) und melden Sie sich an
2. Klicken Sie auf das "+" Symbol oben rechts und wählen Sie "New repository"
3. Repository Name: `bookshare`
4. Description: `Laravel BookShare Platform - Buchverwaltung und Verleihsystem`
5. Wählen Sie "Public" (öffentlich)
6. **WICHTIG**: Haken Sie NICHT "Initialize this repository with a README" an
7. Klicken Sie "Create repository"

## Schritt 2: Personal Access Token erstellen

1. Gehen Sie zu GitHub Settings → Developer settings → Personal access tokens → Tokens (classic)
2. Klicken Sie "Generate new token (classic)"
3. Token Name: `BookShare MCP Access`
4. Wählen Sie diese Scopes:
    - `repo` (Full control of private repositories)
    - `workflow` (Update GitHub Action workflows)
    - `write:packages` (Upload packages to GitHub Package Registry)
    - `delete:packages` (Delete packages from GitHub Package Registry)

## Schritt 3: Repository mit lokalem Projekt verbinden

```bash
# Remote hinzufügen (ersetzen Sie USERNAME mit Ihrem GitHub Benutzernamen)
git remote add origin https://github.com/USERNAME/bookshare.git

# Branch umbenennen auf main (modern GitHub standard)
git branch -M main

# Code zu GitHub pushen
git push -u origin main
```

## Schritt 4: MCP GitHub Tools konfigurieren

Nach der Repository-Erstellung können wir alle GitHub-Operationen über MCP verwalten:

-   Repository-Informationen abrufen
-   Issues erstellen und verwalten
-   Pull Requests handhaben
-   Dateien direkt über MCP bearbeiten
-   Commits und Branches verwalten

## Verfügbare MCP GitHub Tools:

### Repository Management

-   `mcp_github_get_repository` - Repository-Details abrufen
-   `mcp_github_create_repository` - Neues Repository erstellen
-   `mcp_github_fork_repository` - Repository forken

### Issues & Pull Requests

-   `mcp_github_create_issue` - Issue erstellen
-   `mcp_github_list_issues` - Issues auflisten
-   `mcp_github_create_pull_request` - Pull Request erstellen
-   `mcp_github_list_pull_requests` - Pull Requests auflisten

### File Management

-   `mcp_github_get_file_contents` - Datei-Inhalte abrufen
-   `mcp_github_create_or_update_file` - Dateien erstellen/bearbeiten
-   `mcp_github_push_files` - Mehrere Dateien gleichzeitig pushen

### Branch Management

-   `mcp_github_list_branches` - Branches auflisten
-   `mcp_github_create_branch` - Neuen Branch erstellen

### Search & Discovery

-   `mcp_github_search_repositories` - Repositories suchen
-   `mcp_github_search_code` - Code suchen
-   `mcp_github_search_issues` - Issues suchen

## Nächste Schritte

Nach der Repository-Erstellung können Sie mir Ihren GitHub Benutzernamen mitteilen, dann kann ich:

1. Repository-Informationen über MCP abrufen
2. Issues für Projektplanung erstellen
3. Code-Änderungen direkt über MCP verwalten
4. GitHub Actions und Workflows einrichten
5. Projekt-Documentation direkt über MCP aktualisieren

Alle GitHub-Operationen werden dann über die MCP-Tools abgewickelt!
