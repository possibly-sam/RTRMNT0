(defun annual (begin end what)
  "Print annual dates from BEGIN to END with WHAT.
BEGIN and END are years (integers).
WHAT is a string to print after each date."
  (interactive "nBegin year: \nnEnd year: \nsWhat: ")
  (let ((current-year begin))
    (while (<= current-year end)
      (insert (format "%d-06-01  %s\n" current-year what))
      (setq current-year (1+ current-year)))))