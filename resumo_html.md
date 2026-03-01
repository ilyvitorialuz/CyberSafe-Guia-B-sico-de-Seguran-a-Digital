# Resumo: Fundamentos do HTML

Este documento apresenta uma síntese dos conceitos fundamentais de HTML discutidos em aula e reforçados pelo material audiovisual.

# 1. O que é HTML?
O HTML (HyperText Markup Language ou Linguagem de Marcação de Hipertexto) é a linguagem base da Web. 

> Nota Importante: O HTML não é uma linguagem de programação, pois não possui lógica computacional (como loops ou condições). É uma **linguagem de marcação** que utiliza etiquetas (tags) para informar ao navegador como o conteúdo deve ser estruturado e exibido.


# 2. Anatomia das Tags e Estrutura Obrigatória

# Anatomia de uma Tag
A maioria das tags no HTML funciona como um "contêiner", composta por:
Tag de Abertura:`<tag>`
Conteúdo: O texto ou elemento que vai dentro.
Tag de Fechamento:`</tag>` (identificada pela barra `/`).

# Estrutura Inicial Obrigatória
Todo documento HTML5 deve seguir este esqueleto básico para ser interpretado corretamente:

1.  `<!DOCTYPE html>`: Declaração que define que o arquivo é um documento HTML5.
2.  `<html>`: A tag raiz que envolve todo o código da página.
3.  `<head>`: O "cabeçalho" técnico. Contém metadados, links para CSS e o título da aba (`<title>`). Não é visível no corpo do site.
4.  `<body>`: O "corpo" do site. Aqui fica tudo o que o usuário final enxerga (textos, imagens, botões).


# 3. Glossário de Tags Principais

Abaixo, as tags mais comuns utilizadas para dar significado ao conteúdo:

| Tag | Nome | Descrição |
| :--- | :--- | :--- |
| `<h1>` a `<h6>` | Títulos | Definem títulos e subtítulos. O `<h1>` é o mais importante e o `<h6>` o de menor nível. |
| `<p>` | Parágrafo | Utilizada para agrupar blocos de texto comum. |
| `<a>` | Âncora | Cria links. O atributo `href` indica o destino do clique. |
| `<img>` | Imagem | Insere figuras. O atributo `src` define a origem da imagem e o `alt` um texto alternativo. |
| `<ul>` / `<li>` | Listas | `<ul>` cria listas não ordenadas (com marcadores) e `<li>` define cada item da lista. |
| `<strong>` | Negrito | Indica que um texto tem forte importância (esteticamente deixa em negrito). |


# 4. Organização com a Tag `<div>`

A tag `<div>` é um elemento de bloco genérico que serve como um contêiner invisível. Ela é fundamental para:

Aninhamento: Permite colocar vários elementos (títulos, parágrafos, imagens) dentro de um único "pacote".
Organização: Ajuda a separar o layout em seções lógicas (ex: topo, conteúdo principal, lateral).
Estilização: Facilita a aplicação de estilos via CSS, permitindo formatar todo o bloco de uma só vez.

