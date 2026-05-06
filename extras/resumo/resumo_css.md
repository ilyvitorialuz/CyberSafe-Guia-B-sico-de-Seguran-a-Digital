# Resumo de CSS

## O que é CSS e para que ele serve

O CSS (Cascading Style Sheets) é a linguagem usada para definir o
estilo visual de uma página web. Enquanto o **HTML** cria a estrutura do
site (títulos, parágrafos, imagens, listas etc.), o **CSS** é
responsável pela aparência desses elementos.

Com CSS podemos alterar: - cores de textos e fundos - tamanhos de
fontes - espaçamentos entre elementos - alinhamento e posicionamento de
itens na página

Em resumo: HTML estrutura o conteúdo e CSS define o visual da
página.

------------------------------------------------------------------------

## Por que usar um arquivo externo (style.css)

A forma mais recomendada de usar CSS é através de um arquivo
externo, normalmente chamado `style.css`.

Esse arquivo é conectado ao HTML usando:

``` html
<link rel="stylesheet" href="style.css">
```

Usar um arquivo externo é importante porque:

-   mantém o código mais organizado
-   separa estrutura (HTML) de estilo (CSS)
-   permite reutilizar o mesmo estilo em **várias páginas**
-   facilita a **manutenção do projeto**

Assim, se for necessário mudar o visual do site, basta alterar apenas o
arquivo CSS.

------------------------------------------------------------------------

# Modelo de Caixa (Box Model)

No CSS, todo elemento HTML é tratado como uma caixa. Esse conceito é
chamado de Box Model.

Essa caixa possui quatro partes principais:

-   Content → conteúdo do elemento (texto ou imagem)
-   Padding → espaço interno
-   Border → borda
-   Margin → espaço externo

------------------------------------------------------------------------

## Diferença entre Margin e Padding

A diferença entre margin e padding está no local onde o espaço é
aplicado.

### Padding

O padding é o espaço interno, ou seja, a distância entre o
conteúdo do elemento e sua borda.

``` css
button {
  padding: 10px;
}
```

Isso faz com que o conteúdo fique mais afastado das bordas do elemento.

### Margin

A margin é o espaço externo, usado para separar um elemento de
outros elementos ao redor.

``` css
button {
  margin: 20px;
}
```

Isso cria um espaço entre o elemento e os outros elementos da página.

**Resumo:**

  Propriedade   Função
  ------------- --------------------------------
  padding       espaço interno do elemento
  margin        espaço externo entre elementos

------------------------------------------------------------------------

# Glossário de Propriedades CSS

### color

Define a **cor do texto**.

``` css
p {
  color: blue;
}
```

### background-color

Define a **cor de fundo** de um elemento.

``` css
div {
  background-color: lightgray;
}
```

### margin

Define o **espaçamento externo** de um elemento.

``` css
div {
  margin: 20px;
}
```

### padding

Define o **espaçamento interno** entre conteúdo e borda.

``` css
div {
  padding: 15px;
}
```

### display

Define **como o elemento aparece na página**.

Alguns valores comuns são: - block - inline - flex

### display: flex

Ativa o **Flexbox**, um sistema de layout que facilita o alinhamento e
organização de elementos.

``` css
.container {
  display: flex;
}
```

------------------------------------------------------------------------

# Uso de Classes no CSS

As **classes** permitem selecionar elementos específicos no HTML para
aplicar estilos.

Elas ajudam a manter o código **organizado**, **reutilizável** e mais
fácil de manter.

### Exemplo

HTML:

``` html
<p class="destaque">Este é um texto importante.</p>
<p>Este é um texto normal.</p>
```

CSS:

``` css
.destaque {
  color: green;
  font-weight: bold;
}
```

No CSS usamos **um ponto (.) antes do nome da classe**.

Nesse exemplo, apenas o primeiro parágrafo recebe o estilo porque ele
possui a classe `destaque`.
