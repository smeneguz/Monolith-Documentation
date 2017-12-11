SHELL := /bin/bash
pdflatex = pdflatex -interaction=nonstopmode
file = glossario
filenewname = Glossario.pdf

doc:
	$(pdflatex) $(file).tex
	$(pdflatex) $(file).tex
	mv $(file).pdf $(filenewname)

clean:
	rm *.{aux,idx,ind,lof,lot,out,toc,acn,acr,alg,glg,glo,gls,ist,pdf,log} 2&> /dev/null || true
