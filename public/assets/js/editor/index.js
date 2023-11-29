const tags_list = [
    "header",
    "nav",
    "main",
    "article",
    "section",
    "aside",
    "footer",
    "h1",
    "h2",
    "h3",
    "h4",
    "h5",
    "h6",
    "p",
    "a",
    "img",
    "video",
    "audio",
    "ul",
    "ol",
    "li",
    "table",
    "tr",
    "th",
    "td",
    "form",
    "input",
    "button",
    "select",
    "textarea",
    "label",
    "iframe",
    "canvas",
    "svg",
    "header",
    "time",
    "abbr",
    "cite",
    "mark",
    "small",
    "strong",
    "em",
    "sup",
    "sub",
    "i",
];
const iframe = document.getElementsByTagName("iframe")[0];
const tags = [];
iframe.addEventListener("load", function () {
    const iframeDocument =
        iframe.contentDocument || iframe.contentWindow.document;
    tags_list.forEach((tag) => {
        tags.push(iframeDocument.querySelector(tag));
    });

    tags.forEach((tag) => {
        if (tag !== null) {
            tag.addEventListener("click", (e) => {
                e.preventDefault();
                if (giveClickAccess(e.target.tagName)) {
                    console.log(e.target.innerText);
                }
            });
        }
    });
});

function giveClickAccess(tag) {
    const tags = ["h1", "p", "small", "span", "strong", "b"];
    for (var i = 0; i < tags.length; i++) {
        if (tags[i].toUpperCase() == tag) {
            return true;
        }
    }

    return false;
}
