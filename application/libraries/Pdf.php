<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

include_once APPPATH . 'third_party/FPDF/fpdf.php';

class Pdf extends FPDF
{

	var $widths;
	var $aligns;
	var $fills;

	function SetWidths($w)
	{
		//Set the array of column widths
		$this->widths = $w;
	}

	function SetAligns($a)
	{
		//Set the array of column alignments
		$this->aligns = $a;
	}

	function SetFills($f)
	{
		//Style : 'D', 'F', or 'DF' or 'FD'
		$this->fills = $f;
	}

	function Row($data)
	{
		//Calculate the height of the row
		$nb = 0;
		for ($i = 0; $i < count($data); $i++)
			$nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));
		$h = 5 * $nb;
		//Issue a page break first if needed
		$this->CheckPageBreak($h);
		//Draw the cells of the row
		for ($i = 0; $i < count($data); $i++) {
			$w = $this->widths[$i];
			$a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
			$f = isset($this->fills[$i]) ? $this->fills[$i] : 'D';
			//Save the current position
			$x = $this->GetX();
			$y = $this->GetY();
			//Draw the border
			$this->Rect($x, $y, $w, $h, $f);
			//Print the text
			$this->MultiCell($w, 5, $data[$i], 0, $a);
			//Put the position to the right of the cell
			$this->SetXY($x + $w, $y);
		}
		//Go to the next line
		$this->Ln($h);
	}

	function CheckPageBreak($h)
	{
		//If the height h would cause an overflow, add a new page immediately
		if ($this->GetY() + $h > $this->PageBreakTrigger)
			$this->AddPage($this->CurOrientation);
	}

	function NbLines($w, $txt)
	{
		//Computes the number of lines a MultiCell of width w will take
		if (!isset($this->CurrentFont))
			$this->Error('No font has been set');
		$cw = &$this->CurrentFont['cw'];
		if ($w == 0)
			$w = $this->w - $this->rMargin - $this->x;
		$wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
		$s = str_replace("\r", '', $txt);
		$nb = strlen($s);
		if ($nb > 0 and $s[$nb - 1] == "\n")
			$nb--;
		$sep = -1;
		$i = 0;
		$j = 0;
		$l = 0;
		$nl = 1;
		while ($i < $nb) {
			$c = $s[$i];
			if ($c == "\n") {
				$i++;
				$sep = -1;
				$j = $i;
				$l = 0;
				$nl++;
				continue;
			}
			if ($c == ' ')
				$sep = $i;
			$l += $cw[$c];
			if ($l > $wmax) {
				if ($sep == -1) {
					if ($i == $j)
						$i++;
				} else
					$i = $sep + 1;
				$sep = -1;
				$j = $i;
				$l = 0;
				$nl++;
			} else
				$i++;
		}
		return $nl;
	}

	function HorizontalGrafik($chartX, $chartY, $data, $barWidth = 10)
	{
		//dimension
		$chartWidth = 160;
		$chartHeight = 90;

		//padding
		$chartTopPadding = 10;
		$chartLeftPadding = 20;
		$chartBottomPadding = 20;
		$chartRightPadding = 5;

		//chart box
		$chartBoxX = $chartX + $chartLeftPadding;
		$chartBoxY = $chartY + $chartTopPadding;
		$chartBoxWidth = $chartWidth - $chartLeftPadding - $chartRightPadding;
		$chartBoxHeight = $chartHeight - $chartBottomPadding - $chartTopPadding;

		//$dataMax
		$dataMax = 0;
		foreach ($data as $item) {
			if ($item['value'] > $dataMax) $dataMax = $item['value'];
		}

		//data step
		$dataStep = 50;

		//set font, line width and color
		$this->SetFont('Arial', '', 9);
		$this->SetLineWidth(0.2);
		$this->SetDrawColor(0);

		//chart boundary
		$this->Rect($chartX, $chartY, $chartWidth, $chartHeight);

		//vertical axis line
		$this->Line(
			$chartBoxX,
			$chartBoxY,
			$chartBoxX,
			($chartBoxY + $chartBoxHeight)
		);
		//horizontal axis line
		$this->Line(
			$chartBoxX - 2,
			($chartBoxY + $chartBoxHeight),
			$chartBoxX + ($chartBoxWidth),
			($chartBoxY + $chartBoxHeight)
		);

		///vertical axis
		//calculate chart's y axis scale unit
		$yAxisUnits = $chartBoxHeight / $dataMax;

		//draw the vertical (y) axis labels
		for ($i = 0; $i <= $dataMax; $i += $dataStep) {
			//y position
			$yAxisPos = $chartBoxY + ($yAxisUnits * $i);
			//draw y axis line
			$this->Line(
				$chartBoxX - 2,
				$yAxisPos,
				$chartBoxX,
				$yAxisPos
			);
			//set cell position for y axis labels
			$this->SetXY($chartBoxX - $chartLeftPadding, $yAxisPos - 2);
			//$pdf->Cell($chartLeftPadding-4 , 5 , $dataMax-$i , 1);---------------
			$this->Cell($chartLeftPadding - 4, 5, $dataMax - $i, 0, 0, 'R');
		}

		///horizontal axis
		//set cells position
		$this->SetXY($chartBoxX, $chartBoxY + $chartBoxHeight);

		//cell's width
		$xLabelWidth = $chartBoxWidth / count($data);

		//$pdf->Cell($xLabelWidth , 5 , $itemName , 1 , 0 , 'C');-------------
		//loop horizontal axis and draw the bar
		$barXPos = 0;
		foreach ($data as $itemName => $item) {
			//print the label
			//$pdf->Cell($xLabelWidth , 5 , $itemName , 1 , 0 , 'C');--------------
			$this->Cell($xLabelWidth, 5, $itemName, 0, 0, 'C');

			///drawing the bar
			//bar color
			$this->SetFillColor($item['color'][0], $item['color'][1], $item['color'][2]);
			//bar height
			$barHeight = $yAxisUnits * $item['value'];
			//bar x position
			$barX = ($xLabelWidth / 2) + ($xLabelWidth * $barXPos);
			$barX = $barX - ($barWidth / 2);
			$barX = $barX + $chartBoxX;
			//bar y position
			$barY = $chartBoxHeight - $barHeight;
			$barY = $barY + $chartBoxY;
			//draw the bar
			$this->Rect($barX, $barY, $barWidth, $barHeight, 'DF');
			//increase x position (next series)
			$barXPos++;
		}

		//axis labels
		$this->SetFont('Arial', 'B', 12);
		$this->SetXY($chartX, $chartY);
		$this->Cell(100, 10, "Amount", 0);
		$this->SetXY(($chartWidth / 2) - 50 + $chartX, $chartY + $chartHeight - ($chartBottomPadding / 2));
		$this->Cell(100, 5, "Series", 0, 0, 'C');
	}

	function VertikalGrafik($chartX, $chartY, $data)
	{
		//dimension
		$chartWidth = 150;
		$chartHeight = 70;

		//padding
		$chartTopPadding = 15;
		$chartLeftPadding = 25;
		$chartBottomPadding = 10;
		$chartRightPadding = 15;

		//chart box
		$chartBoxX = $chartX + $chartLeftPadding;
		$chartBoxY = $chartY + $chartTopPadding;
		$chartBoxWidth = $chartWidth - $chartLeftPadding - $chartRightPadding;
		$chartBoxHeight = $chartHeight - $chartBottomPadding - $chartTopPadding;

		//$dataMax
		$dataMax = 0;
		$dataTotal = 0;
		foreach ($data as $item) {
			$dataTotal += $item['value'];
			if ($item['value'] > $dataMax) $dataMax = $item['value'];
		}

		//data step
		$dataStep = 5;

		if ($dataMax < $dataStep) {
			if ($dataMax > 0) {
				$dataStep = $dataMax;
			} else {
				$dataStep = 1;
			}
		}

		//set font, line width and color
		$this->SetFont('Arial', '', 8);
		$this->SetLineWidth(0.3);
		$this->SetDrawColor(0);

		//chart boundary
		$this->Rect($chartX, $chartY, $chartWidth, $chartHeight);

		//vertical axis line
		$this->Line(
			$chartBoxX,
			$chartBoxY - 3,
			$chartBoxX,
			($chartBoxY + $chartBoxHeight)
		);

		///vertical axis
		//calculate chart's x axis scale unit
		if ($dataMax > 0) {
			$xAxisUnits = $chartBoxWidth / $dataMax;
		} else {
			$xAxisUnits = 0;
		}

		//draw the vertical (y) axis labels
		$this->SetLineWidth(0.1);
		for ($i = 0; $i <= $dataMax; $i += $dataStep) {
			//x position
			$xAxisPos = $chartBoxX + ($xAxisUnits * $i);
			//draw y grid
			$this->Line(
				$xAxisPos,
				$chartBoxY - 5,
				$xAxisPos,
				$chartBoxY + $chartBoxHeight
			);
			//set cell position for x axis labels
			$this->SetXY($xAxisPos - 1, $chartBoxY - 10);
			$this->Cell(3, 5, $i, 0, 0, 'C');
		}

		///horizontal axis
		//set cells position
		$barYPos = 0;
		$barHeight = $chartBoxHeight / count($data);

		//Y axes
		$this->SetFont('Arial', '', 8);
		foreach ($data as $itemName => $item) {
			$this->SetY($chartBoxY + $barHeight * $barYPos + $barHeight / 2);
			$this->SetX($chartX); //posisi frame sebelah kiri
			$this->MultiCell($chartLeftPadding - 1, 4, $itemName, 0, 'R');
			$barYPos++;
		}

		//Gambar grafik-nya
		$this->SetFont('Arial', '', 6); //font untuk menulis value
		$barYPos = 0;
		$xAwal = $chartBoxX;
		foreach ($data as $itemName => $item) {
			$yAwal = $chartBoxY + $barHeight * $barYPos + 2;
			$barWidth = $xAxisUnits * $item['value'];
			if ($dataTotal > 0) {
				$persen = $item['value'] / $dataTotal * 100;
			} else {
				$persen = 0.0;
			}
			$this->SetY($chartBoxY + $barHeight * $barYPos + $barHeight / 3);
			$this->SetX($chartBoxX + $barWidth);
			$this->MultiCell(20, 3, $item['value'] . "\r\n(" . number_format($persen, 2) . '%)', 0, 'L');

			$this->SetFillColor($item['color'][0], $item['color'][1], $item['color'][2]);
			$this->Rect($xAwal, $yAwal, $barWidth, $barHeight - 2, 'DF');
			$barYPos++;
		}

		//axis labels
		$this->SetFont('Arial', '', 10);
		$this->SetXY($chartX, $chartY);
		$this->Cell($chartLeftPadding, 10, "Kriteria", 0, 0, 'C');
		$this->SetXY(($chartWidth / 2) - 50 + $chartX, $chartY + $chartHeight - ($chartBottomPadding / 2));
		$this->Cell(100, 5, "Jumlah (Persentase)", 0, 0, 'C');
	}
}
