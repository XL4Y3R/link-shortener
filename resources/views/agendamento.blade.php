<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Agendamento</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-light">

  <div class="container py-5">
    <div class="card shadow">
      <div class="card-header">
        <h4 class="mb-0">Agende seu atendimento</h4>
      </div>
      <div class="card-body">
        <form id="form-agendamento" method="post">
          <div class="mb-3">
            <label class="form-label">Nome</label>
            <input type="text" name="name" id="name" class="form-control" required />
          </div>

          <div class="mb-3">
            <label class="form-label">Telefone</label>
            <input type="text" name="phone" id="phone" class="form-control" required />
          </div>

          <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email"  id="email" class="form-control" />
          </div>

          <div class="mb-3">
            <label class="form-label">Data</label>
            <input type="date" name="date" id="date" class="form-control" required />
          </div>

          <div class="mb-3">
            <label class="form-label">Horário</label>
            <select name="time" id="time" class="form-select" disabled>
              <option value="">Selecione uma data primeiro</option>
            </select>
            <div class="form-text text-danger mt-1 d-none" id="msg-horario"></div>
          </div>

          <button type="button" class="btn btn-primary w-100" id="btn-agendar" name="btn-agendar" >
            Agendar
          </button>
        </form>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const apiToken = 'Bearer f62cfa835ccb11f6ae940b09cef84a54cb353b8989f79cfab9e0a846997a575e'

      const $date = document.getElementById('date')
      const $time = document.getElementById('time')
      const $btn = document.getElementById('btn-agendar')
      const $msg = document.getElementById('msg-horario')

      $date.addEventListener('change', async () => {
        const dateValue = $date.value
        $time.innerHTML = '<option>Carregando...</option>'
        $time.disabled = true
        $btn.disabled = true
        $msg.classList.add('d-none')

        try {
          const response = await fetch(`https://teste.xl4y3r.com/api/available-times?date=${dateValue}`, {
            headers: { Authorization: apiToken }
          })

          if (!response.ok) throw new Error('Erro ao buscar horários')

          const horarios = await response.json()
          $time.innerHTML = ''

          if (!horarios.length) {
            $time.innerHTML = '<option value="">Nenhum horário disponível</option>'
            $msg.textContent = 'Nenhum horário disponível para esta data.'
            $msg.classList.remove('d-none')
          } else {
            $time.innerHTML = '<option value="">Selecione</option>'
            horarios.forEach(h => {
              const hora = typeof h === 'string' ? h : h.time
              const opt = document.createElement('option')
              opt.value = hora
              opt.textContent = hora
              $time.appendChild(opt)
            })
            $time.disabled = false
          }
        } catch (err) {
          $time.innerHTML = '<option value="">Erro ao carregar horários</option>'
          $msg.textContent = 'Erro ao buscar horários. Tente novamente.'
          $msg.classList.remove('d-none')
        }
      })

      $time.addEventListener('change', () => {
        $btn.disabled = !$time.value
      })

      $btn.addEventListener('click', async (e) => {
        e.preventDefault()
        const data = {
            name: document.getElementById('name').value,
            phone: document.getElementById('phone').value,
            email: document.getElementById('email').value,
            date: document.getElementById('date').value,
            time: document.getElementById('time').value
        }

        if (!data.date || !data.time) {
            alert('Preencha a data e o horário.')
            return
        }

        $btn.disabled = true
        $btn.textContent = 'Agendando...'

        try {
            const response = await fetch('https://teste.xl4y3r.com/api/appointments', {
            method: 'POST',
            headers: {
                'content-type': 'application/json',
                'authorization': 'Bearer f62cfa835ccb11f6ae940b09cef84a54cb353b8989f79cfab9e0a846997a575e'
            },
            body: JSON.stringify({
                customer_name: data.name,
                phone: data.phone,
                email: data.email,
                scheduled_at: `${data.date} ${data.time}`
            })
            })

            if (!response.ok) {
            let erroMsg = `Erro ${response.status}`
            try {
                const erro = await response.json()
                erroMsg = erro.message || erroMsg
            } catch (_) {}
            throw new Error(erroMsg)
            }

            alert('Agendamento realizado com sucesso!')
            document.getElementById('form-agendamento').reset()
            $time.innerHTML = '<option value="">Selecione uma data primeiro</option>'
            $time.disabled = true
            $btn.disabled = true
            } catch (err) {
                alert('Erro: ' + err.message)
            } finally {
                $btn.textContent = 'Agendar'
            }
        })

    })
    </script>


</body>
</html>
